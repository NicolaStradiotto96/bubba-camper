<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ⚠️ Contabile non valida

Ciao **{{ $booking->customer_first_name }}**,

la contabile caricata per {{ $item['type'] === 'danno' ? 'il danno' : 'la penale' }} <code class="damage">#{{ $item['id'] }}</code> relativo alla prenotazione <code class="booking">#{{ $booking->id }}</code> purtroppo non è valida.

**Motivazione:**
<x-mail::panel>
{{ $reason }}
</x-mail::panel>

**Caricala nuovamente per permetterci di completare la verifica.**

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
CARICA NUOVA CONTABILE
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>