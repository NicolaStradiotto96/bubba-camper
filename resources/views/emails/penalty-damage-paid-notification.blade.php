<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ✅ Danno regolarizzato

È stato registrato il pagamento del danno <code class="damage">#{{ $damage->id }}</code> per la prenotazione <code class="booking">#{{ $damage->booking_id }}</code>.

<div style="margin: 20px 0; border-top: 1px solid #374151;"></div>

## 👤 Dati Cliente
- **Nome:** <span class="highlight">{{ $damage->booking->customer_first_name }} {{ $damage->booking->customer_last_name }}</span>
- **Email:** <a href="mailto:{{ $damage->booking->customer_email }}"  class="highlight">{{ $damage->booking->customer_email }}</a>
- **Telefono:** <span class="highlight">{{ $damage->booking->customer_phone }}</span>

<div style="margin: 20px 0; border-top: 1px solid #374151;"></div>

## 🛠 Dettagli Danno
- **Descrizione:** <span class="highlight">{{ $damage->description }}</span>
- **Importo Pagato:** <span class="highlight">{{ number_format($damage->amount, 2, ',', '.') }}€</span>

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
VAI ALLA DASHBOARD
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Email generata automaticamente dal sistema di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>