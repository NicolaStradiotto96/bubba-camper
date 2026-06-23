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

<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# 🚐 Prenotazione Confermata

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

## 💳 Riepilogo Pagamenti
- **Totale Prenotazione:** <span class="highlight">{{ number_format($booking->total_price, 2, ',', '.') }}€</span>
- **Acconto versato (30%):** <span class="highlight">{{ number_format($booking->down_payment, 2, ',', '.') }}€</span>
- **Saldo rimanente al ritiro (70%):** <span class="highlight">{{ number_format($booking->balance_payment, 2, ',', '.') }}€</span>

<div class="divider"></div>

## 📍 Cosa succede ora?
Prepara le valigie! Il tuo camper ti aspetta.

Se hai domande, puoi rispondere direttamente a questa email.

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
VAI ALLA TUA DASHBOARD
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Grazie per aver scelto {{ config('app.name', 'Bubba Camper') }}!<br>
Buon viaggio 🐶
</div>

</div>
</x-mail::message>