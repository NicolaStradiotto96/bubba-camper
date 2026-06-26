<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ⏳ Il tuo camper ti aspetta!

Ciao **{{ $booking->customer_first_name }}**,

abbiamo visto che hai iniziato la prenotazione per il camper **{{ $booking->camper->name }}**, ma non hai completato il pagamento.

Le date selezionate sono ancora riservate per te, ma solo per poco tempo! Completa il pagamento ora per assicurarti il camper ed evitare che la prenotazione scada.

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
COMPLETA IL PAGAMENTO
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>