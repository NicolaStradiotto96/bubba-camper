<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ⚠️ Documenti non validi

Ciao **{{ $booking->customer_first_name }}**,

alcuni documenti per la prenotazione <code class="booking">#{{ $booking->id }}</code> non sono validi:

@foreach($fields as $field)
- {{ isset($labels[$field]) ? $labels[$field] : 'Nome non trovato: ' . $field }}
@endforeach

**Caricali subito per completare le verifiche necessarie per confermare la tua prenotazione.**

<x-mail::button :url="config('app.url') . '/dashboard?open_doc_modal=' . $booking->id" color="amber">
CARICA I DOCUMENTI
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>