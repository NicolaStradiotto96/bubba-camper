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
    .booking { background: #111827; padding: 2px 5px; color: #f59e0b; }
</style>

<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ❌ Prenotazione Annullata

È stata registrata una operazione di annullamento per la prenotazione <code class="booking">#{{ $booking->id }}</code>.

<div class="divider"></div>

## 👤 Dati Cliente
- **Nome:** <span class="highlight">{{ $booking->customer_first_name }} {{ $booking->customer_last_name }}</span>
- **Email:** <a href="'mailto:' . {{ $booking->customer_email }}" class="highlight">{{ $booking->customer_email }}</a>
- **Telefono:** <span class="highlight">{{ $booking->customer_phone }}</span>

<div class="divider"></div>

## 📅 Dettagli Prenotazione
- **Camper:** <span class="highlight">{{ $booking->camper->name }}</span>
- **Periodo:** <span class="highlight">{{ $booking->start_date->format('d/m/Y') }} - {{ $booking->end_date->format('d/m/Y') }}</span>

<div class="divider"></div>

@if($booking->status === 'cancelled_by_admin')
**Attenzione:** La prenotazione è stata annullata direttamente dall'amministrazione. Procedere con il rimborso totale di **{{ number_format($booking->down_payment + $booking->balance_payment, 2, ',', '.') }}€**.
@elseif($booking->payment_status === 'penalty_pending')
**Attenzione:** Il sistema ha calcolato una **penale di {{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€** a carico del cliente. Il cliente è stato avvisato di procedere con il pagamento tramite dashboard.
@endif

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
VAI ALLA DASHBOARD
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Email generata automaticamente dal sistema di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>