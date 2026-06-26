<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# 🚐 Nuova Richiesta

Hai ricevuto una nuova richiesta dal sito **{{ config('app.name', 'Bubba Camper') }}**.

<div class="divider"></div>

## 👤 Dati Cliente
- **Nome:** <span class="highlight">{{ $data['name'] }}</span>
- **Email:** <a href="'mailto:' . $data['email']" class="highlight">{{ $data['email'] }}</a>

<div class="divider"></div>

## 📅 Periodo richiesto
- **Data inizio:** <span class="highlight">{{ $data['start_date'] ?? 'Non specificata' }}</span>
- **Data fine:** <span class="highlight">{{ $data['end_date'] ?? 'Non specificata' }}</span>

<div class="divider"></div>

## 📝 Messaggio
{{ $data['message'] }}

<x-mail::button :url="'mailto:' . $data['email']" color="amber">
RISPONDI AL CLIENTE
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Email generata automaticamente dal sistema di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>