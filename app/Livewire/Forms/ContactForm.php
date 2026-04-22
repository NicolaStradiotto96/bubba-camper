<?php

namespace App\Livewire\Forms;

use App\Mail\ContactRequest;
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

            $this->start_date = (!empty($dates[0])) ? trim($dates[0]) : null;
            $this->end_date = (!empty($dates[1])) ? trim($dates[1]) : null;
        } else {
            $this->start_date = trim($value);
            $this->end_date = null;
        }
    }

    public function getBookedDatesProperty()
    {
        // BOOKED DATES
        return \App\Models\Booking::where('status', '!=', 'cancelled')
            ->get(['start_date', 'end_date'])
            ->flatMap(function ($booking) {
                $dates = [];
                $current = \Carbon\Carbon::parse($booking->start_date);
                $end = \Carbon\Carbon::parse($booking->end_date);

                while ($current <= $end) {
                    $dates[] = $current->format('d-m-Y');
                    $current->addDay();
                }
                return $dates;
            })
            ->values()
            ->toArray();
    }

    public function sendEmail()
    {
        // HONEYPOT
        if (!empty($this->website)) {

            return;
        }

        // SPEED CHECK
        if (microtime(true) - $this->loadTime < 3) {
            logger()->warning('Invio troppo rapido del form della sezione contatti', [
                'ip' => request()->ip(),
            ]);

            session()->flash('error', 'Invio troppo rapido.');

            return;
        }

        // RATE LIMITER
        $key = 'contact-form:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            logger()->warning('Troppo tentativi di invio del form della sezione contatti', [
                'ip' => request()->ip(),
            ]);

            session()->flash('error', 'Troppi tentativi, riprova più tardi.');

            return;
        }

        RateLimiter::hit($key, 60);

        // VALIDATION
        $validated = $this->validate([
            'website' => 'nullable',
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email:rfc,dns',
            'date_range' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'message' => 'required|string|min:10|max:2000',
        ]);

        // SEND EMAIL
        try {
            $dataForEmail = $validated;

            if ($this->start_date) {
                $dataForEmail['start_date'] = Carbon::parse($this->start_date)->format('d-m-Y');
            }
            if ($this->end_date) {
                $dataForEmail['end_date'] = Carbon::parse($this->end_date)->format('d-m-Y');
            }

            Mail::to('info@bubbacamper.com')->send(new ContactRequest($dataForEmail));

            $this->reset();

            $this->loadTime = microtime(true);

            session()->flash('success', 'Messaggio inviato con successo!');
        } catch (\Exception $e) {

            // LOGGER + ERRORS
            logger()->error('Errore invio mail contatti: ' . $e->getMessage());

            session()->flash('error', 'Errore durante l\'invio. Riprova più tardi.');
        }
    }
}
