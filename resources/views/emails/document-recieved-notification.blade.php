<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# 📄 Documenti ricevuti

Il cliente **{{ $booking->customer_first_name }} {{ $booking->customer_last_name }}** ha appena caricato i documenti per la prenotazione <code class="booking">#{{ $booking->id }}</code>.

Ti invitiamo ad accedere al pannello amministrativo per verificare la validità dei documenti e procedere con l'approvazione finale.

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
VAI ALLA DASHBOARD
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Email generata automaticamente dal sistema di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>