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
    public $website, $name, $email, $date_range, $start_date, $end_date, $message;

    public function mount()
    {
        $this->loadTime = microtime(true);
    }

    public function updatedDateRange($value)
    {
        // SPLIT DATE PERIOD
        if (!empty($value) && str_contains($value, ' al ')) {
            $dates = explode(' al ', $value);
            $this->start_date = isset($dates[0]) ? trim($dates[0]) : null;
            $this->end_date = isset($dates[1]) ? trim($dates[1]) : null;
        } elseif (!empty($value) && str_contains($value, ' to ')) {
            $dates = explode(' to ', $value);
            $this->start_date = isset($dates[0]) ? trim($dates[0]) : null;
            $this->end_date = isset($dates[1]) ? trim($dates[1]) : null;
        } else {
            $this->start_date = !empty($value) ? trim($value) : null;
            $this->end_date = !empty($value) ? trim($value) : null;
        }
    }

    public function sendEmail()
    {
        // HONEYPOT
        if (!empty($this->website)) {
            logger()->warning('Rilevato bot nel form della sezione contatti', [
                'ip' => request()->ip(),
            ]);

            return;
        }

        // SPEED CHECK
        if (microtime(true) - $this->loadTime < 2) {
            session()->flash('error', 'Invio troppo rapido.');
            return;
        }

        // RATE LIMITER
        $key = 'contact-form:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
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

        try {
            Mail::to('info@bubbacamper.com')->send(new ContactRequest($validated));

            $this->reset();

            $this->loadTime = microtime(true);

            session()->flash('success', 'Messaggio inviato con successo!');
        } catch (\Exception $e) {
            logger()->error('Errore invio mail contatti: ' . $e->getMessage());
            session()->flash('error', 'Errore durante l\'invio. Riprova più tardi.');
        }
    }
}
