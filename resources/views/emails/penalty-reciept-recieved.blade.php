<x-mail::message>
<style>
    .body { background-color: #111827 !important; }
    .wrapper { background-color: #111827 !important; }
    .content { background-color: #111827 !important; }
    .header a { color: #f59e0b !important; }
    .footer p { color: #6b7280 !important; }
    
    .inner-body { 
        background-color: #1f2937 !important; 
        border-color: #374151 !important;
        border-radius: 8px !important;
    }
    
    h1, h2, p, li, strong { color: #f3f4f6 !important; }
    .booking { background: #111827; padding: 2px 5px; color: #f59e0b; }
</style>

<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# 🧾 Ricevuta caricata - In verifica

Ciao **{{ $booking->customer_first_name }}**,

ti confermiamo di aver ricevuto la contabile per la penale relativa alla prenotazione <code class="booking">#{{ $booking->id }}</code>.

Il nostro team verificherà l'effettivo accredito del bonifico bancario entro le prossime 48 ore lavorative. 

Una volta completata la verifica, riceverai una notifica di conferma e la pratica di annullamento verrà ufficialmente chiusa.

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>