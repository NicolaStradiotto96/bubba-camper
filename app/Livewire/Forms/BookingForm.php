<?php

namespace App\Livewire\Forms;

use App\Models\Booking;
use App\Models\Camper;
use App\Models\Maintenance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class BookingForm extends Component
{

    public Camper $camper;
    public $date_range;
    public $start_date;
    public $end_date;
    public $total_price = 0;
    public $days_count = 0;
    public $terms_accepted = false;
    public $privacy_accepted = false;

    public function mount(Camper $camper)
    {
        $this->camper = $camper;
    }

    // CALENDAR
    protected function splitDates($value)
    {
        $separator = ' al ';

        if (!empty($value) && str_contains($value, $separator)) {
            $dates = explode($separator, $value);
            try {
                $this->start_date = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->format('Y-m-d');
                $this->end_date = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->format('Y-m-d');
            } catch (\Exception $e) {
                $this->start_date = null;
                $this->end_date = null;
            }
        }
    }

    public function getBookedDatesProperty()
    {
        $limitDate = now()->addMonths(12);

        $bookings = Booking::where('camper_id', $this->camper->id)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where('start_date', '<=', $limitDate)
            ->where(function ($query) {
                $query->where('payment_status', 'paid')
                    ->orWhere('created_at', '>=', now()->subMinutes(15));
            })
            ->get(['start_date', 'end_date']);

        $maintenances = Maintenance::where('camper_id', $this->camper->id)
            ->where('start_date', '<=', $limitDate)
            ->get(['start_date', 'end_date']);

        $allDates = $bookings->concat($maintenances)
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
            ->toArray();

        return array_values(array_unique($allDates));
    }

    // PRICES
    protected function calculateBooking()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $this->days_count = $start->diffInDays($end) + 1;

        if ($this->days_count < 2) {
            $this->total_price = 0;
            return;
        }

        $total = 0;

        $period = CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            $total += $this->getDayPrice($date);
        }

        $this->total_price = $total;
    }

    protected function getDayPrice($date)
    {
        if (method_exists($this->camper, 'getPriceForDate')) {
            return $this->camper->getPriceForDate($date);
        }

        $month = $date->month;
        if (in_array($month, [7, 8])) {
            return $this->camper->price_high ?? 140;
        }
        if (in_array($month, [4, 5, 6, 9, 10])) {
            return $this->camper->price_medium ?? 120;
        }
        return $this->camper->price_low ?? 100;
    }

    // CREATE BOOKING
    public function saveBooking()
    {
        if (!$this->start_date || !$this->end_date) {
            $this->addError('date_range', 'Seleziona un intervallo di date valido.');
            return;
        }

        $this->calculateBooking();

        $this->validate([
            'date_range' => 'required',
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before:' . now()->addMonths(12)->addDay()->format('Y-m-d')
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                'before:' . now()->addMonths(13)->format('Y-m-d')
            ],
            'days_count' => 'required|integer|min:2',
            'total_price' => 'required|numeric|min:1',
            'terms_accepted' => 'required|accepted',
            'privacy_accepted' => 'required|accepted',
        ], [
            'days_count.min' => 'Il noleggio minimo è di 2 giorni.',
            'total_price.min' => 'Errore nel calcolo del prezzo.',
            'start_date.after_or_equal' => 'La data di inizio non può essere nel passato.',
            'start_date.before' => 'Puoi prenotare al massimo entro i prossimi 12 mesi.',
            'end_date.after' => 'La data di fine deve essere successiva a quella di inizio.',
            'end_date.before' => 'La data di fine eccede il limite massimo di prenotazione consentito.',
            'terms_accepted.accepted' => 'È obbligatorio accettare il contratto di noleggio per procedere.',
            'privacy_accepted.accepted' => 'È obbligatorio accettare l\'informativa sulla privacy per procedere.',
        ]);

        $alreadyBooked = Booking::where('camper_id', $this->camper->id)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where(function ($q) {
                $q->where('payment_status', 'paid')
                    ->orWhere('created_at', '>=', now()->subMinutes(15));
            })
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereRaw('? BETWEEN start_date AND DATE_ADD(end_date, INTERVAL 1 DAY)', [$this->start_date])
                    ->orWhereRaw('DATE_ADD(?, INTERVAL 1 DAY) >= start_date AND ? <= end_date', [$this->end_date, $this->start_date])
                    ->orWhere(function ($sub) {
                        $sub->where('start_date', '>=', $this->start_date)
                            ->where('end_date', '<=', $this->end_date);
                    });
            })
            ->exists();

        $isUnderMaintenance = Maintenance::where('camper_id', $this->camper->id)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                    ->orWhere(function ($q) {
                        $q->where('start_date', '<=', $this->start_date)
                            ->where('end_date', '>=', $this->end_date);
                    });
            })
            ->exists();

        if ($alreadyBooked || $isUnderMaintenance) {
            $this->addError('date_range', 'Il camper non è disponibile nelle date selezionate.');
            $this->dispatch('clear-calendar');
            return;
        }

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'customer_first_name' => auth()->user()->first_name,
            'customer_last_name' => auth()->user()->last_name,
            'customer_email' => auth()->user()->email,
            'customer_phone' => auth()->user()->phone,
            'camper_id' => $this->camper->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'terms_accepted' => true,
            'privacy_accepted' => true,
            'terms_and_privacy_accepted_at' => now(),
            'terms_and_privacy_accepted_ip' => request()->ip(),
            'contract_version' => config('contracts.active_version'),
            'total_price' => $this->total_price,
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        $this->dispatch('clear-calendar');

        $this->reset(['date_range', 'start_date', 'end_date', 'total_price', 'days_count', 'terms_accepted', 'privacy_accepted']);

        return redirect()->route('checkout', $booking);
    }

    // UPDATE ERRORS
    public function updated($propertyName, $value)
    {
        if ($propertyName === 'date_range') {
            $this->resetErrorBag(['date_range', 'days_count', 'terms_accepted', 'privacy_accepted']);

            if (empty($value)) {
                $this->total_price = 0;
                $this->days_count = 0;
                $this->addError('date_range', 'Seleziona un intervallo di almeno 2 giorni.');
                return;
            }

            $this->splitDates($value);

            if ($this->start_date && $this->end_date) {
                $this->calculateBooking();

                $this->resetErrorBag(['date_range', 'days_count', 'terms_accepted', 'privacy_accepted']);

                $this->validateOnly('days_count', [
                    'days_count' => 'integer|min:2'
                ], [
                    'days_count.min' => 'Il noleggio deve essere di almeno 2 giorni.'
                ]);
            }
        }
    }

    // RENDER
    public function render()
    {
        return view('livewire.forms.booking-form');
    }
}
