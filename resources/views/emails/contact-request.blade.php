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
</style>

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
Email generata automaticamente dal sistema di Bubba Camper 🐶
</div>

</div>
</x-mail::message>