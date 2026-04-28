<x-mail::message>
# ❌ Prenotazione Annullata

Ciao **{{ $booking->customer_first_name }}**,

ti informiamo che la tua prenotazione **#{{ $booking->id }}** è stata annullata.

@if($booking->payment_status === 'paid')
## 💰 Informazioni sul rimborso
Abbiamo già provveduto ad emettere il **rimborso totale** dell'importo versato. Riceverai l'accredito sul tuo conto entro 5-10 giorni lavorativi, a seconda del tuo istituto bancario.
@endif

Per qualsiasi domanda o chiarimento, rispondi pure a questa email.

A presto,  
**Il team di Bubba Camper** 🚐
</x-mail::message>