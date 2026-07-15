<?php

namespace App\Livewire\Forms;

use App\Mail\ContactRequest;
use App\Models\Booking;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ContactForm extends Component
{
    public function render()
    {
        return view('livewire.forms.contact-form');
    }

    public $loadTime;
    public $website;
    public $name;
    public $email;
    public $date_range;
    public $start_date;
    public $end_date;
    public $message;

    public function mount()
    {
        $this->loadTime = microtime(true);
    }

    // RULES
    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email:rfc,dns',
            'date_range' => 'nullable',
            'start_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
                'before:' . now()->addMonths(12)->addDay()->format('Y-m-d')
            ],
            'end_date' => [
                'nullable',
                'date',
                'after:start_date',
                'before:' . now()->addMonths(13)->format('Y-m-d')
            ],
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    public function updatedDateRange($value)
    {
        // RESET EMPTY DATES
        if (empty($value)) {
            $this->start_date = null;
            $this->end_date = null;
            return;
        }

        // SPLIT DATES PERIOD
        $separator = ' al ';

        if (str_contains($value, $separator)) {
            $dates = explode($separator, $value);

            $rawStart = (!empty($dates[0])) ? trim($dates[0]) : null;
            $rawEnd = (!empty($dates[1])) ? trim($dates[1]) : null;

            try {
                $this->start_date = $rawStart ? Carbon::createFromFormat('d-m-Y', $rawStart)->format('Y-m-d') : null;
                $this->end_date = $rawEnd ? Carbon::createFromFormat('d-m-Y', $rawEnd)->format('Y-m-d') : null;
            } catch (\Exception $e) {
                $this->start_date = null;
                $this->end_date = null;
            }
        } else {
            try {
                $this->start_date = Carbon::createFromFormat('d-m-Y', trim($value))->format('Y-m-d');
                $this->end_date = null;
            } catch (\Exception $e) {
                $this->start_date = null;
                $this->end_date = null;
            }
        }

        $this->validateOnly('start_date');
        $this->validateOnly('end_date');
    }

    public function getBookedDatesProperty()
    {
        $limitDate = now()->addMonths(12);

        $bookings = Booking::query()
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where('start_date', '<=', $limitDate)
            ->where(function ($query) {
                $query->where('payment_status', 'paid')
                    ->orWhere('created_at', '>=', now()->subMinutes(15));
            })
            ->get(['start_date', 'end_date']);

        $maintenances = Maintenance::where('start_date', '<=', $limitDate)
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

    public function sendEmail()
    {
        // HONEYPOT
        if (!empty($this->website)) {

            return;
        }

        // SPEED CHECK
        if (microtime(true) - $this->loadTime < 1) {
            logger()->warning('Invio troppo rapido del form della sezione contatti', [
                'ip' => request()->ip(),
            ]);

            $this->dispatch('swal-error', ['message' => 'Invio troppo rapido.']);

            return;
        }

        // RATE LIMITER
        $key = 'contact-form:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            logger()->warning('Troppo tentativi di invio del form della sezione contatti', [
                'ip' => request()->ip(),
            ]);

            $this->dispatch('swal-error', ['message' => 'Troppi tentativi, riprova più tardi.']);

            return;
        }

        RateLimiter::hit($key, 60);

        // VALIDATION
        $validated = $this->validate();

        // SEND EMAIL
        try {
            $dataForEmail = $validated;

            if ($this->start_date) {
                $dataForEmail['start_date'] = Carbon::parse($this->start_date)->format('d-m-Y');
            }
            if ($this->end_date) {
                $dataForEmail['end_date'] = Carbon::parse($this->end_date)->format('d-m-Y');
            }

            Mail::to(config('app.admin_email'))->send(new ContactRequest($dataForEmail));

            $this->reset(['name', 'email', 'date_range', 'message']);

            $this->dispatch('form-reset');

            $this->loadTime = microtime(true);

            $this->dispatch('swal-success', ['message' => 'Messaggio inviato con successo!']);
        } catch (\Exception $e) {

            // LOGGER + ERRORS
            logger()->error('Errore invio mail contatti: ' . $e->getMessage());

            $this->dispatch('swal-error', ['message' => 'Errore durante l\'invio. Riprova più tardi.']);
        }
    }

    // UPDATE ERRORS
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
