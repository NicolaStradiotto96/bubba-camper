<?php

namespace App\Livewire\Forms;

use App\Mail\ContactRequest;
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

    public function mount()
    {
        $this->loadTime = microtime(true);
    }

    public $website, $name, $email, $start_date, $end_date, $message;

    public function sendEmail()
    {
        // HONEYPOT
        if (!empty($this->website)) {
            logger()->warning('Bot detected in contact form', [
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'message' => 'required|string|min:10|max:2000',
        ]);

        try {
            Mail::to('info@bubbacamper.com')->send(new ContactRequest($validated));

            $this->reset();

            session()->flash('success', 'Messaggio inviato con successo!');
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante l\'invio. Riprova più tardi.');
        }
    }
}
