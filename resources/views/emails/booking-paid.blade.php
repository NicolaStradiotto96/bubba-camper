<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# 🚐 Pagamento Ricevuto

Ciao **{{ $booking->customer_first_name }}**,

abbiamo ricevuto correttamente l'acconto di {{ number_format($booking->down_payment, 2, ',', '') }}€ per la tua prenotazione <code class="booking">#{{ $booking->id }}</code>.

<div class="divider"></div>

## 📄 Il prossimo passo: Documenti
Per procedere con la prenotazione, abbiamo bisogno di verificare la tua identità. Ti chiediamo cortesemente di caricare nella tua area personale:

- **Patente di guida** (fronte e retro)
- **Carta d'identità** (fronte e retro)

Una volta caricati, il nostro team verificherà tutto nel minor tempo possibile e ti invierà la **conferma ufficiale** della prenotazione.

*Nota: La tua prenotazione sarà ufficialmente confermata solo dopo il controllo dei documenti da parte del nostro staff.*

<x-mail::button :url="config('app.url') . '/dashboard?open_doc_modal=' . $booking->id" color="amber">
CARICA I DOCUMENTI
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>