<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# 🧾 Ricevuta caricata - In verifica

Ciao **{{ $booking->customer_first_name }}**,

ti confermiamo di aver ricevuto la contabile relativa alla prenotazione <code class="booking">#{{ $booking->id }}</code>.

Il nostro team verificherà l'effettivo accredito del bonifico bancario entro le prossime 48 ore lavorative. 

Una volta completata la verifica, riceverai una notifica di conferma e la pratica verrà ufficialmente chiusa.

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>