<x-mail::message>
# 🚐 Nuova richiesta

Hai ricevuto una nuova richiesta dal sito **{{ config('app.name') }}**.

---

## 👤 Dati cliente
- **Nome:** {{ $data['name'] }}
- **Email:** <a href="'mailto:' . $data['email']">{{ $data['email'] }}</a>

---

## 📅 Periodo richiesto
- **Data inizio:** {{ $data['start_date'] ?? 'Non specificata' }}
- **Data fine:** {{ $data['end_date'] ?? 'Non specificata' }}

---

## 📝 Messaggio
{{ $data['message'] }}

---

<x-mail::button :url="'mailto:' . $data['email']" color="amber">
Rispondi al cliente
</x-mail::button>

<p style="font-size: 0.8em; color: #6b7280; text-align: center;">
Questa email è stata generata automaticamente dal sistema di Bubba Camper.
</p>

</x-mail::message>