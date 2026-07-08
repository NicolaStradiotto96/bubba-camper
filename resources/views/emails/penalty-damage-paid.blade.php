<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ✅ Danno regolarizzato

Ciao **{{ $damage->booking->customer_first_name }}**,

ti confermiamo di aver ricevuto correttamente il pagamento di **{{ number_format($damage->amount, 2, ',', '.') }}€** relativo al danno <code class="booking">#{{ $damage->id }}</code> segnalato per la prenotazione <code class="booking">#{{ $damage->booking_id }}</code>.

**Dettaglio della pratica:**
<x-mail::panel>
{{ $damage->description }}
</x-mail::panel>

La pratica relativa a questo danno è ora considerata chiusa.

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>