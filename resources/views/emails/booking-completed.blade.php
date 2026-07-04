<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# ✅ Saldo Ricevuto

Ciao **{{ $booking->customer_first_name }}**,

ti **confermiamo** di aver ricevuto correttamente il saldo relativo al noleggio della prenotazione <code class="booking">#{{ $booking->id }}</code>.

La tua pratica è ora amministrativamente conclusa e tutto è pronto per la partenza!

Riceverai la fattura entro 12 giorni lavorativi.

<div class="divider"></div>

## 🔔 Promemoria:
- **Assistenza:** In caso di emergenza tecnica durante il viaggio, il numero di riferimento è <span class="highlight">+39 334 753 8083</span>
- **Manuale:** Ti ricordiamo che sul camper è presente il manuale d'uso. Ti preghiamo di consultarlo per gestire correttamente il tuo viaggio
- **Foto e Avventure:** Se avrai voglia di scattare delle foto durante il viaggio, saremo felici di vederle! Taggaci su Instagram <span class="highlight">@BubbaCamper</span> o invicele via WhatsApp

Se hai domande, puoi rispondere direttamente a questa email.

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Grazie per aver scelto {{ config('app.name', 'Bubba Camper') }}!<br>
Buon viaggio 🐶
</div>

</div>
</x-mail::message>