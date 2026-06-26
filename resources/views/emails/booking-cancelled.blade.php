<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ❌ Prenotazione Annullata

Ciao **{{ $booking->customer_first_name }}**,

ti informiamo che la tua prenotazione <code class="booking">#{{ $booking->id }}</code> è stata annullata.

<div class="divider"></div>

@if($booking->status === 'cancelled_by_admin')
@php
    $totalPaid = 0;
    if ($booking->down_paid) { $totalPaid += $booking->down_payment; }
    if ($booking->balance_paid) { $totalPaid += $booking->balance_payment; }
@endphp
## 💶 Informazioni sul rimborso
La prenotazione è stata annullata dallo staff. Abbiamo emesso il **rimborso totale di {{ number_format($totalPaid, 2, ',', '.') }}€**, corrispondente all'importo da te versato.

L'accredito avverrà entro 5-10 giorni lavorativi, a seconda del tuo istituto bancario.
@endif

@if($booking->status === 'cancelled' && ($booking->payment_status === 'refunded_stripe' || $booking->payment_status === 'refunded_manual'))
## 💶 Informazioni sul rimborso
La tua richiesta di annullamento è stata elaborata. Abbiamo emesso un **rimborso di {{ number_format($booking->calculateExpectedRefund()['refund_amount'], 2, ',', '.') }}€**, al netto della penale prevista dai nostri termini di servizio.

L'accredito avverrà entro 5-10 giorni lavorativi, a seconda del tuo istituto bancario.
@endif

@if($booking->payment_status === 'penalty_pending')
## ⚠️ Pagamento penale
A seguito dell'annullamento, è stata applicata una **penale di {{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€**, come previsto dai nostri termini di servizio.

Ti invitiamo ad accedere alla tua **dashboard** il prima possibile per procedere al saldo della penale dovuta.
@endif

Se hai domande, puoi rispondere direttamente a questa email.

@if($booking->payment_status === 'penalty_pending')
<x-mail::button :url="config('app.url') . '/dashboard?pay_penalty=' . $booking->id" color="amber">
PAGA PENALE
</x-mail::button>
@endif


<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>