<x-app-layout>
    <div class="min-h-[calc(100vh-160px)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-200 dark:border-gray-700 p-8">

                <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wide text-center">
                    Termini e Condizioni d'Uso del Servizio e del Sito Web
                </h1>

                <p class="text-xs text-gray-500 dark:text-gray-400 mb-8 leading-relaxed text-center font-sans">
                    Le presenti Condizioni Generali regolano l'accesso, la navigazione e l'utilizzo del sito web di
                    <strong>Bubba Camper di Stradiotto Stefano</strong> (con sede in Via Chemin Palma 2/C, 36065
                    Mussolente - VI, P.IVA: 02403740240, di seguito il "Locatore") e il servizio di prenotazione online
                    dei veicoli ricreazionali (camper). L'utilizzo del sito, la registrazione dell'account e l'invio di
                    una proposta di prenotazione implicano l'accettazione integrale delle presenti Condizioni.
                </p>

                <div class="space-y-6 text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-sans">

                    <!-- Articolo 1 -->
                    <section class="space-y-2">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 text-base uppercase tracking-wider">Art. 1
                            - Registrazione, Account e Log di Sistema</h2>
                        <p><strong>1.1.</strong> Per procedere alla prenotazione di un camper tramite la piattaforma,
                            l'utente è tenuto a registrarsi fornendo in modo veritiero e completo i propri dati
                            anagrafici (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">first_name</code>, <code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">last_name</code>, <code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">email</code>, <code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">phone</code>) e una
                            password di accesso.</p>
                        <p><strong>1.2.</strong> L'utente è responsabile della custodia delle proprie credenziali di
                            accesso e di ogni attività posta in essere tramite il proprio account.</p>
                        <p><strong>1.3. Registrazione di Log e Tracciabilità di Sistema:</strong> Durante la
                            navigazione, l'autenticazione, la gestione delle prenotazioni e lo svolgimento delle
                            operazioni sul sito, la piattaforma registra automaticamente i log di sistema (inclusi
                            tipologia di evento, messaggi di riscontro, contesti tecnici, indirizzi IP, sessioni e
                            timestamp). Tali dati vengono trattati e conservati per finalità di sicurezza informatica,
                            prevenzione di abusi o frodi, adempimento di obblighi di legge e per garantire piena
                            tracciabilità e opponibilità delle accettazioni telematiche e contrattuali.</p>
                    </section>

                    <!-- Articolo 2 -->
                    <section class="space-y-2">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 text-base uppercase tracking-wider">Art. 2
                            - Catalogo Camper, Durata Minima, Preventivi e Conclusione del Contratto</h2>
                        <p><strong>2.1.</strong> Il catalogo online mostra i veicoli attivi (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">is_active</code>), le
                            relative descrizioni, immagini, attributi tecnici e i listini prezzi dinamici (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">prices</code>).</p>
                        <p><strong>2.2.</strong> L'utente seleziona il veicolo (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">camper_id</code>,
                            tramite il relativo slug) e il periodo di interesse (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">start_date</code>,
                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">end_date</code>), nel
                            rispetto di una durata minima del noleggio pari a 2 giorni; il sistema calcola
                            automaticamente il preventivo totale (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">total_price</code>).
                        </p>
                        <p><strong>2.3. Caparra confirmatoria (Down Payment):</strong> Per completare la proposta di
                            prenotazione online, il cliente è obbligato a versare contestualmente una caparra pari al
                            30% dell'importo totale (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">down_payment</code>)
                            tramite il gateway di pagamento elettronico Stripe (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">stripe_payment_id</code>).
                            Il contratto si intende concluso solo a seguito dell'avvenuto incasso della caparra (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">down_paid =
                                true</code>).</p>
                        <p><strong>2.4. Accettazione telematica e tracciabilità:</strong> Durante la procedura di
                            checkout, l'utente deve accettare espressamente i Termini e Condizioni e la Privacy Policy
                            tramite apposite spunte (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">terms_accepted</code>,
                            <code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">privacy_accepted</code>).
                            Il sistema memorizza l'accettazione associandola alla versione specifica del contratto
                            (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">contract_version</code>),
                            alla data/ora esatta (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">terms_and_privacy_accepted_at</code>)
                            e all'indirizzo IP del dispositivo (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">terms_and_privacy_accepted_ip</code>).
                        </p>
                    </section>

                    <!-- Articolo 3 -->
                    <section class="space-y-2">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 text-base uppercase tracking-wider">Art. 3
                            - Caricamento Documenti, Consenso e Verifica dell'Identità</h2>
                        <p><strong>3.1.</strong> Subito dopo la conferma della prenotazione e il pagamento della
                            caparra, per finalizzare al 100% la validità del viaggio, il cliente ha l'obbligo di
                            caricare sulla piattaforma le copie digitali in formato fronte e retro dei seguenti
                            documenti:</p>
                        <ul class="list-disc pl-6 space-y-1">
                            <li>Patente di guida in corso di validità (<code
                                    class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">driver_license_front_path</code>,
                                <code
                                    class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">driver_license_back_path</code>);
                            </li>
                            <li>Documento d'identità / Carta d'identità (<code
                                    class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">id_card_front_path</code>,
                                <code
                                    class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">id_card_back_path</code>).
                            </li>
                        </ul>
                        <p><strong>3.2.</strong> Il caricamento dei documenti richiede l'autorizzazione esplicita al
                            trattamento tramite apposita spunta (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">documents_accepted =
                                true</code>), di cui il sistema memorizza data, ora (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">documents_accepted_at</code>)
                            e indirizzo IP (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">documents_accepted_ip</code>).
                        </p>
                        <p><strong>3.3.</strong> Il caricamento dei file viene registrato con uno stato iniziale di
                            attesa (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">documents_status =
                                'pending'</code>).</p>
                        <p><strong>3.4. Conferma Amministrativa:</strong> Il viaggio e la prenotazione saranno ritenuti
                            definitivamente confermati (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">status =
                                'confirmed'</code>) solo dopo la verifica e l'approvazione manuale dei documenti da
                            parte dell'amministrazione.</p>
                    </section>

                    <!-- Articolo 4 -->
                    <section class="space-y-2">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 text-base uppercase tracking-wider">Art. 4
                            - Saldo del Corrispettivo e Deposito Cauzionale</h2>
                        <p><strong>4.1. Saldo (Balance Payment):</strong> Il restante 70% del corrispettivo di noleggio
                            (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">balance_payment</code>)
                            deve essere corrisposto dal cliente:</p>
                        <ul class="list-disc pl-6 space-y-1">
                            <li>Almeno 3 giorni prima dell'inizio del noleggio, se effettuato tramite bonifico bancario;
                            </li>
                            <li>Oppure direttamente in loco, il giorno del ritiro del veicolo, a mezzo carta di credito
                                o contanti.</li>
                        </ul>
                        <p class="mt-1">Il mancato pagamento del saldo nei termini costituisce causa di risoluzione
                            immediata del contratto ex art. 1457 c.c., con perdita della caparra e diritto del Locatore
                            a esigere l'intera penale contrattuale.</p>
                        <p><strong>4.2. Deposito Cauzionale:</strong> Al momento del ritiro del veicolo presso la sede
                            di Mussolente (VI), il cliente è tenuto a versare un deposito cauzionale pari a 500€ a
                            garanzia del corretto uso del mezzo, secondo le modalità previste dalle Condizioni Generali
                            di Noleggio.</p>
                    </section>

                    <!-- Articolo 5 -->
                    <section class="space-y-2">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 text-base uppercase tracking-wider">Art. 5
                            - Chilometraggio e Regole di Percorrenza</h2>
                        <p><strong>5.1.</strong> Ciascun giorno di noleggio comprende una soglia massima di 150 KM
                            inclusi.</p>
                        <p><strong>5.2.</strong> Eventuali chilometri percorsi in eccesso rispetto alla franchigia
                            giornaliera cumulata verranno addebitati al termine del noleggio secondo la tariffa
                            chilometrica stabilita pari a 0,20€ per KM.</p>
                    </section>

                    <!-- Articolo 6 -->
                    <section class="space-y-2">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 text-base uppercase tracking-wider">Art. 6
                            - Annullamento, Recesso e Gestione Rimborsi/Penali</h2>
                        <p><strong>6.1.</strong> In caso di recesso unilaterale o richiesta di annullamento da parte del
                            cliente (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">cancellation_requested_at</code>),
                            si applicano le penali (multa penitenziale ex art. 1373 c.c.) calcolate sul costo
                            dell'intera locazione in base ai giorni di anticipo rispetto alla data di consegna:</p>
                        <ul class="list-disc pl-6 space-y-1">
                            <li>A partire dal 61° giorno antecedente: penale del 10%;</li>
                            <li>Dal 60° al 31° giorno antecedente: penale del 50%;</li>
                            <li>Dal 30° al 11° giorno antecedente: penale dell'80%;</li>
                            <li>Dal 10° giorno fino al giorno previsto per la consegna (incluso il mancato ritiro /
                                no-show): penale del 100%.</li>
                        </ul>
                        <p class="mt-2"><strong>6.2.</strong> Sulla base delle scadenze e dei parametri di cui sopra,
                            il sistema calcola in modo automatico l'importo della penale applicata (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">penalty_amount</code>),
                            l'eventuale importo da rimborsare al netto delle trattenute (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">refund_amount</code>) e
                            l'eventuale conguaglio o penale residua dovuta dal cliente (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">remaining_penalty</code>),
                            qualora la penale superi la caparra confirmatoria precedentemente versata.</p>
                        <p><strong>6.3.</strong> Eventuali storni, rimborsi approvati (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">refund_receipt_path</code>,
                            <code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">refund_paid_at</code>)
                            o quietanze di penale regolarizzata (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">penalty_receipt_path</code>,
                            <code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">penalty_paid_at</code>)
                            verranno gestiti e registrati amministrativamente sulla piattaforma.</p>
                        <p><strong>6.4.</strong> In caso di pagamento di penali, conguagli o importi residui (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">remaining_penalty</code>)
                            tramite bonifico bancario, il cliente ha l'onere di caricare tempestivamente la relativa
                            contabile di pagamento sulla piattaforma (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">penalty_receipt_path</code>).
                            L'operazione di annullamento o regolarizzazione si intenderà definitivamente perfezionata
                            solo dopo la verifica e l'approvazione della contabile da parte dell'amministrazione.</p>
                    </section>

                    <!-- Articolo 7 -->
                    <section class="space-y-2">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 text-base uppercase tracking-wider">Art. 7
                            - Verifica Post-Riconsegna e Gestione Danni</h2>
                        <p><strong>7.1.</strong> Ai sensi delle Condizioni Generali di Noleggio, il Locatore si riserva
                            il termine perentorio di 7 giorni lavorativi successivi alla riconsegna del veicolo per
                            effettuare verifiche approfondite sul mezzo.</p>
                        <p><strong>7.2.</strong> Qualora in tale finestra temporale dovessero emergere danni occulti,
                            guasti, difetti o ammanchi non immediatamente riscontrabili al momento della restituzione
                            materiale, la piattaforma registrerà formalmente la contestazione associandola alla
                            prenotazione nella tabella dedicata (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">damages</code>).</p>
                        <p><strong>7.3.</strong> Ciascuna contestazione di danno conterrà un importo di addebito (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">amount</code>), una
                            descrizione dettagliata (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">description</code>),
                            uno stato di gestione (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">status</code>),
                            l'eventuale documentazione o contabile di pagamento del risarcimento caricata a sistema
                            (<code class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">receipt_path</code>)
                            e le relative prove fotografiche digitali caricate a sistema (<code
                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">damage_photos</code>),
                            autorizzando espressamente il Locatore a rivalersi sul deposito cauzionale o a richiedere il
                            pagamento della differenza. L'eventuale regolarizzazione tramite bonifico o pagamento online
                            della pratica di danno si intenderà definitiva solo dopo la verifica e l'approvazione da
                            parte dell'amministrazione.</p>
                    </section>

                    <!-- Articolo 8 -->
                    <section class="space-y-2">
                        <h2 class="font-bold text-gray-800 dark:text-gray-200 text-base uppercase tracking-wider">Art. 8
                            - Ritiro, Orari e Condizioni Contrattuali</h2>
                        <p><strong>8.1.</strong> Il ritiro e la riconsegna del veicolo avvengono presso la sede di Bubba
                            Camper in Via Chemin Palma 2/C, Mussolente (VI), nel rispetto delle fasce orarie di
                            assistenza e operatività (dal lunedì alla domenica, dalle 10:00 alle 13:00 e dalle 16:00
                            alle 20:00). Eventuali variazioni di orario rispetto alle finestre standard devono essere
                            concordate preventivamente con la direzione.</p>
                        <p><strong>8.2.</strong> Il giorno stabilito per il ritiro, il cliente firmerà materialmente il
                            contratto cartaceo originale e il verbale di consegna con verifica congiunta delle
                            condizioni del veicolo e dei chilometri.</p>
                        <p><strong>8.3.</strong> Per supporto, comunicazioni o chiarimenti, il cliente può fare
                            riferimento al numero telefonico dedicato +39 334 753 8083 o all'indirizzo email
                            amministrativo.</p>
                        <p><strong>8.4.</strong> Per tutto quanto non espressamente disciplinato nelle presenti
                            condizioni d'uso del sito web (inclusi gli obblighi di guida, divieti di fumo/animali,
                            gestione dei guasti, sinistri, franchigie e foro competente), si fa integrale rinvio alle
                            Condizioni Generali di Contratto di Noleggio di Bubba Camper visionabili in allegato e
                            accettate in sede di prenotazione.</p>
                    </section>

                </div>

                <div class="mt-10 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-black text-sm uppercase tracking-widest rounded-[2rem] focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                        Torna Indietro
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
