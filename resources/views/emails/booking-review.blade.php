<x-mail::message>
<div style="background-color: #1f2937; padding: 20px; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

# 🚐 Com'è andata l'avventura?

Ciao **{{ $booking->customer_first_name }}**,

speriamo che il vostro viaggio con il nostro <span class="highlight">{{ $booking->camper->name }}</span> sia stato indimenticabile e pieno di bei momenti!

Ci farebbe davvero piacere sapere com'è andata la vostra esperienza con **{{ config('app.name') }}**.

Le vostre recensioni sono fondamentali per aiutarci a crescere e per permettere ad altri viaggiatori di scegliere con serenità.

<x-mail::button :url="'https://search.google.com/local/writereview?placeid=ChIJS8YBp9XZeEcR1oqeRrjQ_vo'">
LASCIA UNA RECENSIONE
</x-mail::button>

<div style="margin-top: 30px; border-top: 1px solid #374151; padding-top: 20px; font-size: 0.9em; color: #9ca3af;">
Grazie per aver scelto {{ config('app.name', 'Bubba Camper') }}!
</div>

</div>
</x-mail::message>