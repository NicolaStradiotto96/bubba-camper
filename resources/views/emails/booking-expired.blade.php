<x-mail::message>
# ⏳ Sessione scaduta

Ciao **{{ $booking->customer_first_name }}**,

ti avvisiamo che la tua sessione per la prenotazione del camper **{{ $booking->camper->name }}** è scaduta perché non è stato completato il pagamento entro i 15 minuti previsti.

Le date che avevi selezionato ({{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}) sono ora tornate **disponibili per altri utenti**.

Se hai avuto un problema tecnico o vuoi semplicemente riprovare, puoi farlo subito cliccando il tasto qui sotto.

<x-mail::button :url="config('app.url')" color="amber">
Verifica disponibilità e riprova
</x-mail::button>

A presto,  
**Il team di Bubba Camper** 🚐
</x-mail::message>