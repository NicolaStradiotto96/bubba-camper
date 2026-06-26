<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ⏳ Prenotazione Scaduta

Ciao **{{ $booking->customer_first_name }}**,

ti avvisiamo che la tua sessione per la prenotazione del camper **{{ $booking->camper->name }}** è scaduta perché non è stato completato il pagamento entro i 15 minuti previsti.

Le date che avevi selezionato ({{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}) sono ora tornate **disponibili per altri utenti**.

<div class="divider"></div>

Se hai avuto un problema tecnico o vuoi semplicemente riprovare, puoi farlo subito cliccando il tasto qui sotto.

<x-mail::button :url="config('app.url') . '/noleggio'" color="amber">
VEDI I NOSTRI CAMPER
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>