<?php

namespace App\Livewire\Admin;

use App\Mail\BookingPaid;
use App\Mail\BookingPaidNotification;
use App\Models\Booking;
use App\Models\Camper;
use App\Models\Log;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class BookingManager extends Component
{
    use WithPagination;

    public $camper_id;
    public $customer_first_name;
    public $customer_last_name;
    public $customer_email;
    public $customer_phone;
    public $date_range;
    public $start_date;
    public $end_date;
    public $total_price;
    public $down_payment = 0;
    public $balance_payment = 0;

    // IS ADMIN?
    public function mount()
    {
        if (!auth()->user()?->is_admin) {
            abort(403);
        }
    }

    // RULES
    protected function rules()
    {
        return [
            'camper_id'           => 'required|exists:campers,id',
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name'  => 'required|string|max:255',
            'customer_email'      => 'required|string|lowercase|email|max:255',
            'customer_phone'      => 'nullable|min:8|max:20',
            'date_range'          => 'required',
            'start_date'          => ['required', 'date', 'after_or_equal:today', 'before:' . now()->addMonths(12)->addDay()->format('Y-m-d')],
            'end_date'            => ['required', 'date', 'after_or_equal:start_date', 'before:' . now()->addMonths(13)->format('Y-m-d')],
            'total_price'         => 'required|numeric|min:0',
        ];
    }

    // ERROR MESSAGES
    protected function messages()
    {
        return [
            'camper_id.required'           => 'Devi selezionare un camper.',
            'camper_id.exists'             => 'Il camper selezionato non è valido.',
            'customer_first_name.required' => 'Il nome è obbligatorio.',
            'customer_last_name.required'  => 'Il cognome è obbligatorio.',
            'customer_email.required'      => 'L\'email è obbligatoria.',
            'customer_email.email'         => 'Inserisci un indirizzo email valido.',
            'customer_phone.min'           => 'Il numero di telefono è troppo breve.',
            'customer_phone.max'           => 'Il numero di telefono è troppo lungo.',
            'date_range.required'          => 'Devi selezionare un periodo di noleggio.',
            'start_date.after_or_equal'    => 'La data di inizio non può essere nel passato.',
            'start_date.before'            => 'La prenotazione deve essere entro i prossimi 12 mesi.',
            'end_date.after_or_equal'      => 'La data di fine deve essere successiva alla data di inizio.',
            'total_price.required'         => 'Il prezzo totale è obbligatorio.',
            'total_price.numeric'          => 'Il prezzo deve essere un numero.',
            'total_price.min'              => 'Il prezzo non può essere negativo.',
        ];
    }

    // SPLIT PRICE
    private function calculatePayments()
    {
        if (!is_numeric($this->total_price)) {
            return;
        }

        $total = (float) $this->total_price;
        $this->down_payment = round($total * 0.30, 2);
        $this->balance_payment = round($total - $this->down_payment, 2);
    }

    // RESET FORM
    public function resetForm()
    {
        $this->reset([
            'camper_id',
            'customer_first_name',
            'customer_last_name',
            'customer_email',
            'customer_phone',
            'date_range',
            'start_date',
            'end_date',
            'total_price',
            'down_payment',
            'balance_payment'
        ]);

        $this->resetErrorBag();

        $this->dispatch('reset-phone');
        $this->dispatch('clear-calendar');
    }

    // SAVE BOOKING
    public function saveManualBooking()
    {
        $this->validate($this->rules(), $this->messages());

        $isUnderMaintenance = Maintenance::where('camper_id', $this->camper_id)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                    ->orWhere(function ($q) {
                        $q->where('start_date', '<=', $this->start_date)
                            ->where('end_date', '>=', $this->end_date);
                    });
            })->exists();

        if ($isUnderMaintenance) {
            $this->addError('date_range', 'Il camper selezionato non è disponibile nel periodo scelto a causa di manutenzione.');
            return;
        }

        $isBooked = Booking::where('camper_id', $this->camper_id)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date]);
            })->exists();

        if ($isBooked) {
            $this->addError('date_range', 'Il camper è già prenotato in questo periodo.');
            return;
        }

        $this->calculatePayments();

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'camper_id' => $this->camper_id,
            'customer_first_name' => $this->customer_first_name,
            'customer_last_name' => $this->customer_last_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_price' => $this->total_price,
            'down_payment' => $this->down_payment,
            'down_paid' => true,
            'down_paid_at' => now(),
            'balance_payment' => $this->balance_payment,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'documents_status' => 'pending',
            'terms_accepted' => true,
            'privacy_accepted' => true,
            'terms_and_privacy_accepted_at' => now(),
            'terms_and_privacy_accepted_ip' => request()->ip(),
            'contract_version' => config('contracts.active_version'),
        ]);

        $this->logBooking('booking_created', "Creata prenotazione manuale #{$booking->id} per camper #{$this->camper_id}", $booking);

        try {
            Mail::to($booking->customer_email)->send(new BookingPaid($booking));
            Mail::to(config('app.admin_email'))->send(new BookingPaidNotification($booking));
        } catch (\Exception $e) {
            \Log::error("Errore invio mail prenotazione manuale #{$booking->id}: " . $e->getMessage());
        }

        $this->reset(['camper_id', 'customer_first_name', 'customer_last_name', 'customer_email', 'customer_phone', 'start_date', 'end_date', 'total_price']);
        $this->dispatch('clear-calendar');

        session()->flash('swal-success', "Prenotazione #{$booking->id} creata con successo!");
        return $this->redirect(route('dashboard'), navigate: true);
    }

    // CALENDAR
    public function getBookedDatesProperty()
    {
        if (!$this->camper_id) {
            return [];
        }

        $limitDate = now()->addMonths(12);

        $bookings = Booking::where('camper_id', $this->camper_id)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where('start_date', '<=', $limitDate)
            ->get(['start_date', 'end_date']);

        $maintenances = Maintenance::where('camper_id', $this->camper_id)
            ->where('start_date', '<=', $limitDate)
            ->get(['start_date', 'end_date']);

        return $bookings->concat($maintenances)
            ->flatMap(function ($item) {
                $isBooking = $item instanceof Booking;

                $start = Carbon::parse($item->start_date);
                $end = Carbon::parse($item->end_date);

                $extendedStart = $isBooking ? $start->subDay() : $start;
                $extendedEnd = $isBooking ? $end->addDays(2) : $end->addDay();

                $period = new \DatePeriod(
                    $extendedStart,
                    new \DateInterval('P1D'),
                    $extendedEnd
                );

                $dates = [];
                foreach ($period as $date) {
                    $dates[] = $date->format('d-m-Y');
                }
                return $dates;
            })
            ->unique()
            ->values()
            ->toArray();
    }

    public function updatedDateRange($value)
    {
        if (empty($value)) return;

        try {
            $separator = ' al ';

            if (str_contains($value, $separator)) {
                $dates = explode($separator, $value);
                $this->start_date = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->format('Y-m-d');
                $this->end_date = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->format('Y-m-d');
            } else {
                $date = Carbon::createFromFormat('d-m-Y', trim($value))->format('Y-m-d');
                $this->start_date = $date;
                $this->end_date = $date;
            }
        } catch (\Exception $e) {
            \Log::error("Errore parsing data in DateRange: " . $e->getMessage() . " Valore ricevuto: " . $value);

            $this->start_date = null;
            $this->end_date = null;
            $this->addError('date_range', 'Errore del formato data.');
        }
    }

    // UPDATE PRICE
    public function updatedTotalPrice()
    {
        $this->calculatePayments();
    }

    // UPDATE ERRORS
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules(), $this->messages());
    }

    // RENDER
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.booking-manager', [
            'bookings' => Booking::with('camper')->latest()->paginate(10),
            'campers'  => Camper::all()
        ]);
    }

    // LOG
    private function logBooking(string $type, string $message, Booking $booking)
    {
        Log::create([
            'booking_id' => $booking->id,
            'type'       => $type,
            'message'    => $message,
            'context'    => [
                'user_id'    => auth()->id(),
                'ip_address' => request()->ip(),
                'camper_id'  => $this->camper_id,
                'period'     => Carbon::parse($this->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($this->end_date)->format('d/m/Y'),
            ],
        ]);
    }
}
