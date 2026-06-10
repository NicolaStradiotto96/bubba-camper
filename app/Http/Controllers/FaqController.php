<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = [
            [
                'q' => 'Cosa è incluso nel prezzo del noleggio?',
                'a' => 'Il prezzo include il chilometraggio indicato, l\'assicurazione Kasko con franchigia, l\'assistenza stradale 24/7, l\'attrezzatura da cucina di base, il cavo elettrico per le colonnine e i cunei livellatori.'
            ],
            [
                'q' => 'Quali documenti devo presentare per il ritiro?',
                'a' => 'Al momento del ritiro è necessario presentare la patente di guida in corso di validità (da almeno 2 anni), una carta d\'identità o passaporto e una carta di credito (non prepagata) intestata al conducente principale per il deposito cauzionale.'
            ],
            [
                'q' => 'Posso portare il mio animale domestico a bordo?',
                'a' => 'Certamente! I nostri camper sono pet-friendly. Ti chiediamo solo di comunicarcelo in fase di prenotazione e di avere particolare cura nel mantenere pulito l\'interno del veicolo.'
            ],
            [
                'q' => 'Come funziona la politica di cancellazione?',
                'a' => 'Puoi annullare la prenotazione in ogni momento. Le penali variano in base al preavviso: 10% se annulli oltre 61 giorni prima, 50% tra 60 e 31 giorni, 80% tra 30 e 11 giorni, e 100% con meno di 10 giorni di preavviso.'
            ],
            [
                'q' => 'Devo restituire il camper pulito?',
                'a' => 'Il camper deve essere restituito con l\'interno pulito, il serbatoio delle acque grigie svuotato e la cassetta WC pulita. Se il veicolo viene restituito eccessivamente sporco, verrà addebitato un costo extra per la pulizia professionale.'
            ],
            [
                'q' => 'È possibile guidare il camper con la patente B?',
                'a' => 'Sì, tutti i nostri camper rientrano nella categoria dei veicoli con peso massimo a pieno carico di 3500 kg, pertanto sono guidabili con la normale patente B conseguita da almeno 2 anni.'
            ],
            [
                'q' => 'Cosa succede in caso di guasto meccanico?',
                'a' => 'Ogni veicolo è coperto da assistenza stradale europea. In caso di guasto, contatta immediatamente il numero dedicato fornito alla consegna: il nostro team di assistenza interverrà nel più breve tempo possibile.'
            ],
            [
                'q' => 'Dove posso consultare il contratto di noleggio?',
                'a' => 'Puoi leggere il contratto di noleggio completo online oppure scaricarlo in formato PDF cliccando qui: <a href="' . asset('storage/contracts/v1/Contratto-Noleggio.pdf') . '" target="_blank" class="text-amber-500 font-semibold hover:underline">Visualizza Contratto PDF</a>'
            ],
        ];

        return view('faq', compact('faqs'));
    }
}
