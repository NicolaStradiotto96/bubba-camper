/* TRANSITION DARK/LIGHT MODE */
const updateTheme = () => {
    if (localStorage.getItem('color-theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
};

updateTheme();

document.addEventListener('livewire:navigated', updateTheme);

// SWEETALERT 2
document.addEventListener('livewire:init', () => {

    // Theme Toggler
    function getSwalTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            isDark: isDark,
            background: isDark ? '#1f2937' : '#ffffff',
            color: isDark ? '#ffffff' : '#111827',
            border: isDark ? '#374151' : '#e5e7eb',
            backdrop: '#000000e6'
        };
    }

    document.addEventListener('click', function (e) {
        const btnDesc = e.target.closest('#btn-description');
        const btnSpecs = e.target.closest('#btn-specs');
        const btnPolicies = e.target.closest('#btn-policies');

        // Camper Specs
        if (btnSpecs) {
            const camperName = btnSpecs.getAttribute('data-name');
            const camperDesc = btnSpecs.getAttribute('data-description');
            const theme = getSwalTheme();

            Swal.fire({
                title: '<i class="fa-solid fa-van-shuttle text-amber-500" style="font-size: 5rem;"></i>',
                text: camperName,
                html: `
                    <div class="w-full font-black text-center">

                        <h2 class="text-4xl text-gray-900 dark:text-white uppercase tracking-tighter text-center mb-3">
                            ${camperName}
                        </h2>
                        <div class="text-base leading-relaxed px-1 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-amber-600 dark:text-amber-500 text-center">
                                ${camperDesc}
                            </p>
                        </div>

                        <div class="text-sm text-gray-700 dark:text-gray-300">

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                    <i class="fa-solid fa-gears text-lg text-amber-500 mr-1"></i> Caratteristiche tecniche
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 justify-center items-center text-gray-600 dark:text-gray-400">
                                    <div class="py-2">Tipologia camper <span class="text-white ml-1">Mansardato</span></div>
                                    <div class="py-2">Marca veicolo <span class="text-white ml-1">Citroën</span></div>
                                    <div class="py-2">Modello veicolo <span class="text-white ml-1">Jumper</span></div>
                                    <div class="py-2">Marca allestimento <span class="text-white ml-1">McLouis</span></div>
                                    <div class="py-2">Modello allestimento <span class="text-white ml-1">Glamys 226</span></div>
                                    <div class="py-2">Paese immatricolazione <span class="text-white ml-1">Italia</span></div>
                                    <div class="py-2">Data immatricolazione <span class="text-white ml-1">04-04-2023</span></div>
                                    <div class="py-2">Peso massimo a pieno carico <span class="text-white ml-1">3500kg</span></div>
                                    <div class="py-2">Lunghezza <span class="text-white ml-1">6.99m</span></div>
                                    <div class="py-2">Larghezza <span class="text-white ml-1">2.35m</span></div>
                                    <div class="py-2">Altezza <span class="text-white ml-1">3.20m</span></div>
                                    <div class="py-2">Capienza serbatoio acqua pulita <span class="text-white ml-1">100L</span></div>
                                    <div class="py-2">Capienza serbatoio acque grigie <span class="text-white ml-1">100L</span></div>
                                    <div class="py-2">Capienza serbatoio acque nere <span class="text-white ml-1">18L</span></div>
                                    <div class="py-2">Carburante utilizzato <span class="text-white ml-1">Diesel</span></div>
                                    <div class="py-2">Capienza serbatoio carburante <span class="text-white ml-1">90L</span></div>
                                    <div class="py-2">Consumo <span class="text-white ml-1">Da 9 a 12L/100km</span></div>
                                    <div class="py-2">Cambio <span class="text-white ml-1">Manuale</span></div>
                                    <div class="py-2">Additivo <span class="text-white ml-1">AdBlue</span></div>
                                    <div class="py-2">Cilindrata <span class="text-white ml-1">2200cc</span></div>
                                    <div class="py-2">Potenza <span class="text-white ml-1">140CV</span></div>
                                </div>
                            </div>

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                    <i class="fa-solid fa-bolt text-lg text-amber-500 mr-1"></i> Autonomia
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 justify-center items-center text-gray-600 dark:text-gray-400">
                                    <div class="py-2">Alimentazione riscaldamento <span class="text-white ml-1">Gas</span></div>
                                    <div class="py-2">Alimentazione scaldacqua <span class="text-white ml-1">Gas</span></div>
                                    <div class="py-2">Alimentazione piano cottura <span class="text-white ml-1">Gas</span></div>
                                    <div class="py-2">Alimentazione frigo <span class="text-white ml-1">Gas - 220V - 12V</span></div>
                                    <div class="py-2">Tipo di prese elettrice <span class="text-white ml-1">220V - 12V - USB</span></div>
                                </div>
                            </div>

                        </div>

                    </div>
                `,
                width: '960px',
                showCloseButton: true,
                confirmButtonText: 'CHIUDI',
                confirmButtonColor: '#d97706',
                background: theme.background,
                backdrop: theme.backdrop,
                color: theme.color,
                didOpen: (popup) => {
                    popup.style.border = `2px solid ${theme.border}`;
                },
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'text-md rounded-xl font-black uppercase tracking-widest px-4 py-2',
                    closeButton: 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 absolute top-1 right-1'
                }
            });
        }

        // Camper Equipment
        if (btnDesc) {
            const camperName = btnDesc.getAttribute('data-name');
            const camperDesc = btnDesc.getAttribute('data-description');
            const theme = getSwalTheme();

            Swal.fire({
                title: '<i class="fa-solid fa-van-shuttle text-amber-500" style="font-size: 5rem;"></i> ',
                text: camperName,
                html: `
                    <div class="w-full font-black text-center">

                        <h2 class="text-4xl text-gray-900 dark:text-white uppercase tracking-tighter text-center mb-3">
                            ${camperName}
                        </h2>
                        <div class="text-base leading-relaxed px-1 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-amber-600 dark:text-amber-500 text-center">
                                ${camperDesc}
                            </p>
                        </div>

                        <div class="text-sm text-gray-700 dark:text-gray-300">

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                    <i class="fa-solid fa-map-location-dot text-base text-amber-500 mr-1"></i> Alla guida
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 justify-center items-center text-gray-600 dark:text-gray-400">
                                    <div class="py-2">Servosterzo</div>
                                    <div class="py-2">Cruise control</div>
                                    <div class="py-2">Telecamera retromarcia</div>
                                    <div class="py-2">Autoradio</div>
                                    <div class="py-2">Airbag</div>
                                    <div class="py-2">Chiusura centralizzata</div>
                                    <div class="py-2">Pneumatici 4 stagioni</div>
                                    <div class="py-2">Catene da neve</div>
                                    <div class="py-2">Cunei livellatori</div>
                                    <div class="py-2">Kit primo soccorso</div>
                                    <div class="py-2">Climatizzatore</div>
                                    <div class="py-2">Riscaldamento</div>
                                </div>
                            </div>

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                    <i class="fa-solid fa-couch text-lg text-amber-500 mr-1"></i> Vita a bordo
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 justify-center items-center text-gray-600 dark:text-gray-400">
                                    <div class="py-2">Sedili girevoli</div>
                                    <div class="py-2">Riscaldamento spazio abitativo</div>
                                    <div class="py-2">Estintore</div>
                                    <div class="py-2">Letto matrimoniale sopra gavone</div>
                                    <div class="py-2">Letto matrimoniale in mansarda</div>
                                    <div class="py-2">Letto matrimoniale in dinette</div>
                                </div>
                            </div>

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3">
                                    <i class="fa-solid fa-utensils text-lg text-amber-500 mr-1"></i> Cucina / Dinette
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 justify-center items-center text-gray-600 dark:text-gray-400">
                                    <div class="py-2">Frigorifero</div>
                                    <div class="py-2">Congelatore</div>
                                    <div class="py-2">Fornelli</div>
                                    <div class="py-2">Tavolo interno</div>
                                    <div class="py-2">Posti a tavola <span class="text-white ml-1">4 + 2 girevoli</span></div>
                                    <div class="py-2">Lavello</div>
                                </div>
                            </div>

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                    <i class="fa-solid fa-shower text-lg text-amber-500 mr-1"></i> Zona bagno
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 justify-center items-center text-gray-600 dark:text-gray-400">
                                    <div class="py-2">Doccia interna</div>
                                    <div class="py-2">WC</div>
                                    <div class="py-2">Lavabo</div>
                                    <div class="py-2">Acqua calda</div>
                                </div>
                            </div>

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                    <i class="fa-solid fa-caravan text-lg text-amber-500 mr-1"></i> Esterno
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 justify-center items-center text-gray-600 dark:text-gray-400">
                                    <div class="py-2">Gavone</div>
                                    <div class="py-2">Tendalino</div>
                                    <div class="py-2">Pannello solare</div>
                                    <div class="py-2">Portabici</div>
                                    <div class="py-2">Posti sul portabici <span class="text-white ml-1">3</span></div>
                                    <div class="py-2">Batteria ausiliaria</div>
                                </div>
                            </div>

                        </div>

                    </div>
                `,
                width: '960px',
                showCloseButton: true,
                confirmButtonText: 'CHIUDI',
                confirmButtonColor: '#d97706',
                background: theme.background,
                backdrop: theme.backdrop,
                color: theme.color,
                didOpen: (popup) => {
                    popup.style.border = `2px solid ${theme.border}`;
                },
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'text-md rounded-xl font-black uppercase tracking-widest px-4 py-2',
                    closeButton: 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 absolute top-1 right-1'
                }
            });
        }

        // Camper Policies
        if (btnPolicies) {
            const camperName = btnPolicies.getAttribute('data-name');
            const camperDesc = btnPolicies.getAttribute('data-description');
            const theme = getSwalTheme();

            Swal.fire({
                title: '<i class="fa-solid fa-van-shuttle text-amber-500" style="font-size: 5rem;"></i> ',
                text: camperName,
                html: `
                    <div class="w-full font-black text-center">

                        <h2 class="text-4xl text-gray-900 dark:text-white uppercase tracking-tighter text-center mb-3">
                            ${camperName}
                        </h2>
                        <div class="text-base leading-relaxed px-1 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-amber-600 dark:text-amber-500 text-center">
                                ${camperDesc}
                            </p>
                        </div>

                        <div class="text-sm text-gray-700 dark:text-gray-300">

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                    <i class="fa-solid fa-hand-holding-dollar text-lg text-amber-500 mr-1"></i> Cauzione
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-2 justify-center items-center text-gray-600 dark:text-gray-400">
                                    <div class="py-2">Modalità di consegna <span class="text-white ml-1">Contanti - Carta</span></div>
                                    <div class="py-2">Importo <span class="text-white ml-1">500€</span></div>
                                </div>
                            </div>

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-white uppercase tracking-widest mb-3 gap-2 flex items-center justify-center">
                                    <i class="fa-solid fa-clock text-lg text-amber-500 mr-1"></i> Ritiro e Riconsegna
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-1 justify-center items-center text-gray-600 dark:text-gray-400">
                                    <div class="py-2">Lun - Ven <span class="text-white ml-1">10:00 - 13:00 / 16:00 - 20:00</span></div>
                                </div>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-1 text-center italic">
                                    * Eventuali variazioni di orario vanno concordate preventivamente.
                                </p>
                            </div>
                        </div>

                            <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                    <i class="fa-solid fa-calendar-xmark text-lg text-amber-500 mr-1"></i> Condizioni di annullamento
                                </h4>

                                <div class="w-full font-sans p-3 text-left block">
            
                                    <div class="relative px-4">

                                        <div class="absolute top-15 sm:top-10 left-4 right-4 h-1 bg-white/75 rounded-full block z-0"></div>

                                        <div class="relative flex justify-between items-center z-10">

                                            <div class="flex flex-col items-center text-center w-1/4">
                                                <span class="text-xs md:text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider block h-8 mb-5 sm:mb-0 px-1">
                                                    Oltre 61 gg
                                                </span>
                                                <div class="w-5 h-5 rounded-full bg-green-500 border-4 border-white dark:border-gray-800 shadow shadow-green-500/50"></div>
                                                <span class="text-xs font-bold text-green-500 mt-2 block h-8 px-1">Penale 10%</span>
                                            </div>

                                            <div class="flex flex-col items-center text-center w-1/4">
                                                <span class="text-xs md:text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider block h-8 mb-5 sm:mb-0 px-1">
                                                    Da 60 a 31 gg
                                                </span>
                                                <div class="w-5 h-5 rounded-full bg-yellow-500 border-4 border-white dark:border-gray-800 shadow shadow-yellow-500/50"></div>
                                                <span class="text-xs font-bold text-yellow-500 mt-2 block h-8 px-1">Penale 50%</span>
                                            </div>

                                            <div class="flex flex-col items-center text-center w-1/4">
                                                <span class="text-xs md:text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider block h-8 mb-5 sm:mb-0 px-1">
                                                    Da 30 a 11 gg
                                                </span>
                                                <div class="w-5 h-5 rounded-full bg-amber-500 border-4 border-white dark:border-gray-800 shadow shadow-amber-500/50"></div>
                                                <span class="text-xs font-bold text-amber-500 mt-2 block h-8 px-1">Penale 80%</span>
                                            </div>

                                            <div class="flex flex-col items-center text-center w-1/4">
                                                <span class="text-xs md:text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider block h-8 mb-5 sm:mb-0 px-1">
                                                    Meno di 10 gg
                                                </span>
                                                <div class="w-5 h-5 rounded-full bg-red-500 border-4 border-white dark:border-gray-800 shadow shadow-red-500/50"></div>
                                                <span class="text-xs font-bold text-red-500 mt-2 block h-8 px-1">Penale 100%</span>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 pt-6 sm:pt-3 text-xs md:text-sm text-center">

                                        <div class="p-3 bg-white dark:bg-gray-900/50 rounded-xl border-b-2 border border-green-500">
                                            <h5 class="font-black text-green-500 uppercase tracking-wider text-xs mb-1">Preavviso sopra i 61 giorni</h5>
                                            <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                                                Trattenuta del <span class="font-black text-gray-900 dark:text-white">10%</span> dell'importo totale.
                                            </p>
                                        </div>

                                        <div class="p-3 bg-white dark:bg-gray-900/50 rounded-xl border-b-2 border border-yellow-500">
                                            <h5 class="font-black text-yellow-500 uppercase tracking-wider text-xs mb-1">Preavviso tra 60 e 31 giorni</h5>
                                            <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                                                Trattenuta del <span class="font-black text-gray-900 dark:text-white">50%</span> dell'importo totale.
                                            </p>
                                        </div>

                                        <div class="p-3 bg-white dark:bg-gray-900/50 rounded-xl border-b-2 border border-amber-500">
                                            <h5 class="font-black text-amber-500 uppercase tracking-wider text-xs mb-1">Preavviso tra 30 e 11 giorni</h5>
                                            <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                                                Trattenuta del <span class="font-black text-gray-900 dark:text-white">80%</span> dell'importo totale.
                                            </p>
                                        </div>

                                        <div class="p-3 bg-white dark:bg-gray-900/50 rounded-xl border-b-2 border border-red-500">
                                            <h5 class="font-black text-red-500 uppercase tracking-wider text-xs mb-1">Preavviso sotto i 10 giorni</h5>
                                            <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                                                Trattenuta del <span class="font-black text-gray-900 dark:text-white">100%</span> dell'importo totale.
                                            </p>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                `,
                width: '960px',
                showCloseButton: true,
                confirmButtonText: 'CHIUDI',
                confirmButtonColor: '#d97706',
                background: theme.background,
                backdrop: theme.backdrop,
                color: theme.color,
                didOpen: (popup) => {
                    popup.style.border = `2px solid ${theme.border}`;
                },
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'text-md rounded-xl font-black uppercase tracking-widest px-4 py-2',
                    closeButton: 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 absolute top-1 right-1'
                }
            });
        }
    });

    // Confirm Booking
    window.confirmHostAction = function (id, firstName, lastName, startDate, endDate) {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'CONFERMA PRENOTAZIONE',
            html: `
                <div class="text-center space-y-4 px-2 font-black">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Vuoi confermare ufficialmente la prenotazione di:
                    </p>

                    <p class="text-xl text-amber-600 dark:text-amber-500 uppercase tracking-wide">
                        ${firstName} ${lastName}
                    </p>

                    <div class="p-3 bg-gray-100 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                        <p class="text-base uppercase text-gray-400 tracking-widest mb-1">Periodo Noleggio</p>
                        <p class="text-base text-gray-800 dark:text-gray-200">
                            ${startDate} <span class="text-amber-500 mx-1">➔</span> ${endDate}
                        </p>
                    </div>
                </div>
            `,
            icon: 'question',
            iconColor: '#d97706',
            showCancelButton: true,
            confirmButtonText: 'PROCEDI',
            confirmButtonColor: '#d97706',
            cancelButtonText: 'CHIUDI',
            background: theme.background,
            color: theme.color,
            didOpen: (popup) => {
                popup.style.border = `2px solid ${theme.border}`;
            },
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'text-md rounded-xl font-black uppercase tracking-widest px-3 py-2',
                cancelButton: 'text-md rounded-xl font-black uppercase tracking-widest px-3 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('confirmBooking', { bookingId: id });
            }
        });
    };

    // Cancel Booking
    window.confirmRefundAction = function (id, amount, penalty_percent, penalty_amount, hasStripe) {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'ANNULLA PRENOTAZIONE',
            html: `
                <div class="text-center space-y-4 px-2 font-black">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2 px-2 tracking-wide">
                        Sei veramente sicuro di voler annullare questa prenotazione? <br>
                        L'azione non è reversibile.
                    </p>
                    <div class="p-3 bg-gray-100 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                        <p class="text-base uppercase text-gray-400 tracking-widest mb-1">Penale Calcolata (${penalty_percent}%)</p>
                        <p class="text-lg text-green-500">${penalty_amount}€</p>
                    </div>
                    <div class="p-3 bg-gray-100 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                        <p class="text-base uppercase text-gray-400 tracking-widest mb-1">Rimborso Dovuto</p>
                        <p class="text-lg text-red-500">${amount}€</p>
                    </div>
                </div>
            `,
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonText: 'PROCEDI',
            confirmButtonColor: '#ef4444',
            cancelButtonText: 'CHIUDI',
            background: theme.background,
            color: theme.color,
            didOpen: (popup) => {
                popup.style.border = `2px solid ${theme.border}`;
            },
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2',
                cancelButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2'
            }

            // Refund
        }).then((result) => {
            if (result.isConfirmed) {
                if (amount > 0 && hasStripe) {
                    Swal.fire({
                        title: 'METODO DI RIMBORSO',
                        text: "Scegli come rimborsare il denaro al cliente:",
                        icon: 'question',
                        iconColor: '#ef4444',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'STRIPE',
                        confirmButtonColor: '#ef4444',
                        denyButtonText: 'MANUALE',
                        denyButtonColor: '#ef4444',
                        cancelButtonText: 'CHIUDI',
                        background: theme.background,
                        color: theme.color,
                        didOpen: (popup) => {
                            popup.style.border = `2px solid ${theme.border}`;
                        },
                        customClass: {
                            popup: 'rounded-xl',
                            confirmButton: 'text-md rounded-xl font-black uppercase tracking-widest px-3 py-2',
                            denyButton: 'text-md rounded-xl font-black uppercase tracking-widest px-3 py-2',
                            cancelButton: 'text-md rounded-xl font-black uppercase tracking-widest px-3 py-2'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('cancelBooking', { bookingId: id, useStripe: true });
                        } else if (result.isDenied) {
                            Livewire.dispatch('cancelBooking', { bookingId: id, useStripe: false });
                        }
                    });
                } else {
                    Livewire.dispatch('cancelBooking', { bookingId: id, useStripe: false });
                }
            }
        });
    };
    // Complete Booking
    window.confirmPayment = function (id, balance) {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'REGISTRA SALDO',
            html: `Hai ricevuto il pagamento finale di <b class="text-green-500 text-xl">${balance}€</b>?<br><small class="text-gray-500">La prenotazione verrà segnata come interamente pagata.</small>`,
            icon: 'success',
            iconColor: '#1fae53',
            showCancelButton: true,
            confirmButtonText: 'PROCEDI',
            confirmButtonColor: '#1fae53',
            cancelButtonText: 'CHIUDI',
            background: theme.background,
            color: theme.color,
            didOpen: (popup) => {
                popup.style.border = `2px solid ${theme.border}`;
            },
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'text-md rounded-xl font-black uppercase tracking-widest px-3 py-2',
                cancelButton: 'text-md rounded-xl font-black uppercase tracking-widest px-3 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('markAsPaid', { bookingId: id });
            }
        });
    };

    // Request Booking Cancellation
    window.requestUserCancellation = function (bookingId, penaltyAmount) {
        const theme = getSwalTheme();

        let message = `Sei sicuro di voler richiedere l'annullamento della prenotazione <span
                                    class="bg-gray-200 dark:bg-gray-900 py-0.5 px-1">#${bookingId}</span>?`;

        if (penaltyAmount > 0) {
            message += `<br><br><span class="text-amber-500 font-bold">Attenzione:</span> In base alle tempistiche contrattuali, è prevista una penale di trattenuta pari a <b>${penaltyAmount}€</b>.`;
        } else {
            message += `<br><br>La tua richiesta sarà presa in carico. Il nostro staff la valuterà e ti darà una risposta il prima possibile.`;
        }

        Swal.fire({
            title: 'ANNULLARE IL VIAGGIO?',
            html: message,
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'PROCEDI',
            cancelButtonText: 'CHIUDI',
            background: theme.background,
            color: theme.color,
            didOpen: (popup) => {
                popup.style.border = `2px solid ${theme.border}`;
            },
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2',
                cancelButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('requestCancellation', { bookingId: bookingId });
            }
        });
    }

    // Pay Penalty
    window.payPenaltyAction = function (bookingId, penaltyAmount) {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'PAGAMENTO PENALE',
            html: `
                <div class="text-center space-y-4 px-2 font-black">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Per completare l'annullamento e regolarizzare la prenotazione <span
                                    class="bg-gray-200 dark:bg-gray-900 py-0.5 px-1">#${bookingId}</span>, è necessario corrispondere la penale contrattuale pari a:
                    </p>

                    <div class="p-3 bg-gray-100 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                        <p class="text-base uppercase text-gray-400 tracking-widest mb-1">Importo da pagare</p>
                        <p class="text-lg text-red-500">${penaltyAmount}€</p>
                    </div>
                </div>
            `,
            icon: 'info',
            iconColor: '#d97706',
            showCancelButton: true,
            confirmButtonText: 'PROCEDI',
            confirmButtonColor: '#d97706',
            cancelButtonText: 'CHIUDI',
            background: theme.background,
            color: theme.color,
            didOpen: (popup) => {
                popup.style.border = `2px solid ${theme.border}`;
            },
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2',
                cancelButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2'
            }
        }).then((result) => {

            // Payment Method
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'METODO DI PAGAMENTO',
                    text: "Seleziona come pagare la penale:",
                    icon: 'question',
                    iconColor: '#d97706',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'STRIPE',
                    confirmButtonColor: '#d97706',
                    denyButtonText: 'BONIFICO',
                    denyButtonColor: '#d97706',
                    cancelButtonText: 'CHIUDI',
                    background: theme.background,
                    color: theme.color,
                    didOpen: (popup) => {
                        popup.style.border = `2px solid ${theme.border}`;
                    },
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2',
                        denyButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2',
                        cancelButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Stripe
                        Livewire.dispatch('processPenaltyPayment', { bookingId: bookingId });
                    } else if (result.isDenied) {

                        // Manual
                        Swal.fire({
                            title: 'CARICA CONTABILE BONIFICO',
                            html: `
                                <div class="text-center space-y-3 px-2 font-black">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Per effettuare il bonifico, esegui il versamento sul seguente conto:
                                    </p>

                                    <div class="p-3 bg-gray-100 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer" title="Fai doppio click per selezionare tutto">
                                        <p class="text-xs uppercase text-gray-400 tracking-widest mb-1 font-sans">IBAN</p>
                                        <p class="font-mono tracking-wider text-amber-600 dark:text-amber-500 select-all">
                                            IT15 I030 0203 2809 3462 9714 728
                                        </p>
                                    </div>

                                    <p class="text-sm text-gray-500 dark:text-gray-400 pt-2">
                                        Dopo aver effettuato il pagamento, allega il PDF/foto della ricevuta:
                                    </p>

                                    <input type="file" id="penalty-file-input" accept=".pdf,.png,.jpg,.jpeg" class="hidden" 
                                        onchange="document.getElementById('file-chosen-text').innerText = this.files[0] ? this.files[0].name : 'Nessun file selezionato'" />
                                                    
                                    <div class="flex flex-col items-center justify-center gap-2 mt-2">
                                        <button type="button" 
                                            onclick="document.getElementById('penalty-file-input').click()"
                                            class="inline-flex items-center gap-2 bg-amber-600 text-white text-xs font-black uppercase tracking-widest py-2 px-4 rounded-xl transition shadow-md cursor-pointer">
                                            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                            Sfoglia...
                                        </button>
                                        <span id="file-chosen-text" class="text-xs text-gray-400 font-sans italic tracking-wide">
                                            Nessun file selezionato
                                        </span>
                                    </div>
                                </div>
                            `,
                            icon: 'question',
                            iconColor: '#d97706',
                            showCancelButton: true,
                            confirmButtonText: 'INVIA',
                            confirmButtonColor: '#d97706',
                            cancelButtonText: 'CHIUDI',
                            background: theme.background,
                            color: theme.color,
                            didOpen: (popup) => {
                                popup.style.border = `2px solid ${theme.border}`;
                            },
                            customClass: {
                                popup: 'rounded-xl',
                                confirmButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2',
                                cancelButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2'
                            },
                            preConfirm: () => {
                                const fileInput = document.getElementById('penalty-file-input');
                                const maxSize = 8 * 1024 * 1024;

                                if (!fileInput.files || fileInput.files.length === 0) {
                                    Swal.showValidationMessage('Devi selezionare un file prima di inviare!');
                                    return false;
                                }

                                if (fileInput.files[0].size > maxSize) {
                                    Swal.showValidationMessage('Il file è troppo grande! Il limite massimo è 5MB.');
                                    return false;
                                }

                                return fileInput.files[0];
                            }
                        }).then((fileResult) => {
                            if (fileResult.isConfirmed && fileResult.value) {
                                const file = fileResult.value;

                                Swal.fire({
                                    title: 'Caricamento in corso...',
                                    allowOutsideClick: false,
                                    didOpen: () => { Swal.showLoading(); }
                                });

                                const formData = new FormData();
                                formData.append('receipt', file);
                                formData.append('booking_id', bookingId);

                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                                fetch('/bookings/upload-receipt', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: formData
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            Livewire.dispatch('processPenaltyBankTransfer', { bookingId: bookingId });
                                            Swal.close();
                                        } else {
                                            Swal.fire('Errore', data.message || 'Errore durante il caricamento del file.', 'error');
                                        }
                                    })
                                    .catch(() => {
                                        Swal.fire('Errore', 'Impossibile connettersi al server per l\'upload.', 'error');
                                    });
                            }
                        });
                    }
                });
            }
        });
    };
});