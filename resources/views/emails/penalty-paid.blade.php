<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ✅ Penale regolarizzata

Ciao **{{ $booking->customer_first_name }}**,

ti confermiamo che il pagamento della penale di {{ number_format(($booking->penalty_amount ?? 0) - ($booking->down_payment ?? 0), 2, ',', '') }}€ per la prenotazione <code class="booking">#{{ $booking->id }}</code> è stato ricevuto correttamente.

La pratica di annullamento è ora considerata chiusa.

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>