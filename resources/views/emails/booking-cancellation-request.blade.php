<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# 📩 Richiesta di Annullamento ricevuta

Ciao **{{ $booking->customer_first_name }}**,

abbiamo ricevuto la tua richiesta di annullamento per la prenotazione <code class="booking">#{{ $booking->id }}</code>.

Il nostro team prenderà in carico la pratica il prima possibile e verificherà lo stato della tua prenotazione in base ai nostri termini di servizio.

<div class="divider"></div>

## ℹ️ Cosa succederà ora?
- Il nostro staff verificherà eventuali rimborsi o penali applicabili.
- Riceverai un'ulteriore comunicazione via email con la conferma dell'annullamento e i dettagli finanziari.

Se hai urgenza o necessità di comunicare ulteriori dettagli, puoi rispondere direttamente a questa email.

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
VAI ALLA TUA DASHBOARD
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>
</div>
</x-mail::message>