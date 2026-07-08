<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# 🧾 Verifica Contabile Richiesta

Un cliente ha caricato la contabile di pagamento per {{ $type === 'Danno' ? 'un danno' : 'la penale' }} della prenotazione <code class="booking">#{{ $booking->id }}</code>.

<div class="divider"></div>

## 👤 Dati Cliente
- **Nome:** <span class="highlight">{{ $booking->customer_first_name }} {{ $booking->customer_last_name }}</span>
- **Email:** <a href="mailto:{{ $booking->customer_email }}" class="highlight">{{ $booking->customer_email }}</a>
- **Telefono:** <span class="highlight">{{ $booking->customer_phone }}</span>

<div class="divider"></div>

## 📅 Dettagli Prenotazione
- **Camper:** <span class="highlight">{{ $booking->camper->name }}</span>
- **Periodo:** <span class="highlight">{{ $booking->start_date->format('d/m/Y') }} - {{ $booking->end_date->format('d/m/Y') }}</span>

<div class="divider"></div>

## 💶 Costi
- **Importo da verificare:** <span class="highlight">{{ number_format($amount, 2, ',', '') }}€</span>

<div class="divider"></div>

## 📝 Prossimi Step
1. Accedi al tuo home banking per verificare l'effettiva ricezione del bonifico.
2. Controlla il documento caricato tramite il pannello di controllo.
3. Se tutto è corretto, procedi alla chiusura definitiva della pratica.

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
VAI ALLA DASHBOARD
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Email generata automaticamente dal sistema di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>