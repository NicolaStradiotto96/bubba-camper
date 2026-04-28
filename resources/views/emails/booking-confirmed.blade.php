<x-mail::message>
<style>
    .body { background-color: #111827 !important; }
    .wrapper { background-color: #111827 !important; }
    .content { background-color: #111827 !important; }
    .header a { color: #f59e0b !important; }
    .footer p { color: #6b7280 !important; }
    
    .inner-body { 
        background-color: #1f2937 !important; 
        border-color: #374151 !important;
        border-radius: 8px !important;
    }
    
    h1, h2, p, li, strong { color: #f3f4f6 !important; }
    .divider { border-bottom: 1px solid #374151 !important; margin: 20px 0; }
    .highlight { color: #f59e0b !important; }
    .booking { background: #111827; padding: 2px 5px; color: #f59e0b; }
</style>

<div style="background-color: #1f2937; padding: 20px; border-radius: 8px;">

# <span>🚐 Viaggio Confermato!</span>

Ciao **{{ $booking->customer_first_name }}**,

Bubba Camper ha appena **confermato** la tua prenotazione.<br>
Il camper è pronto per la tua prossima avventura.

<div class="divider"></div>

## 📅 Dettagli del Noleggio
- **Camper:** <span class="highlight">{{ $booking->camper->name }}</span>
- **Dal:** <span class="highlight">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span>
- **Al:** <span class="highlight">{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</span>
- **Codice prenotazione:** <code class="booking">#{{ $booking->id }}</code>

<div class="divider"></div>

## 📍 Cosa succede ora?
Prepara le valigie! Ti invieremo a breve i dettagli per il ritiro del mezzo e il manuale di bordo in formato PDF.

Se hai domande, puoi rispondere direttamente a questa email.

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
Vai alla tua Dashboard
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Grazie per aver scelto {{ config('app.name', 'Bubba Camper') }}!<br>
Buon viaggio
</div>

</div>
</x-mail::message>