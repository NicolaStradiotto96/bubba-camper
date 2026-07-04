<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

@if($isUpdate)
# 🔄 Aggiornamento Danno

Ciao **{{ $damage->booking->customer_first_name }}**,

L'importo relativo alla segnalazione del danno per la prenotazione <code class="booking">#{{ $damage->booking_id }}</code> relativa al veicolo <strong class="highlight">{{ $damage->booking->camper->name ?? 'N/A' }}</strong> è stato **aggiornato**.
@else
# ⚠️ Segnalazione Danno

Ciao **{{ $damage->booking->customer_first_name }}**,

Ti scriviamo in merito alla prenotazione <code class="booking">#{{ $damage->booking_id }}</code> del camper <strong class="highlight">{{ $damage->booking->camper->name ?? 'N/A' }}</strong>.

A seguito delle verifiche effettuate alla riconsegna del veicolo, abbiamo riscontrato la necessità di segnalare il seguente danno:
@endif

<x-mail::panel>
{{ $damage->description }}
</x-mail::panel>

Importo {{ $isUpdate ? 'aggiornato' : 'previsto' }}:
<x-mail::panel>
<div style="text-align: center;">
<strong class="highlight">{{ number_format($damage->amount, 2, ',', '') }} €</strong>
</div>
</x-mail::panel>

@if($damage->photos->count() > 0)
<div style="text-align: center;">
<strong>📸 Foto allegate:</strong>
</div>

<div style="text-align: center;">
@foreach($damage->photos as $photo)
<img src="{{ url(Storage::url($photo->path)) }}" style="width: 200px; margin: 5px; border-radius: 4px;">
@endforeach
</div>
@endif

<x-mail::button :url="config('app.url') . '/dashboard'" color="amber">
PAGA PENALE
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Il team di {{ config('app.name', 'Bubba Camper') }} 🐶
</div>

</div>
</x-mail::message>