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

    // Refund
    window.confirmRefundAction = function (id, amount, penalty_percent, penalty_amount, hasStripe) {
        const theme = getSwalTheme();
        const amountToRefund = parseFloat(amount);

        const askRefundMethod = (applyPenalty, byAdmin) => {
            if (amountToRefund > 0 && hasStripe) {
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
                        confirmButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2',
                        denyButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2',
                        cancelButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed || result.isDenied) {
                        Livewire.dispatch('cancelBooking', {
                            bookingId: id,
                            applyPenalty: applyPenalty,
                            byAdmin: byAdmin,
                            useStripe: result.isConfirmed
                        });
                    }
                });
            } else {
                Livewire.dispatch('cancelBooking', {
                    bookingId: id,
                    applyPenalty: applyPenalty,
                    byAdmin: byAdmin,
                    useStripe: false
                });
            }
        };

        // Cancel Booking
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
            showDenyButton: true,
            confirmButtonText: 'CON PENALE',
            confirmButtonColor: '#ef4444',
            denyButtonText: 'SENZA PENALE',
            denyButtonColor: '#ef4444',
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
                askRefundMethod(true, false)
            } else if (result.isDenied) {
                askRefundMethod(false, true);
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

    // Invoice
    window.confirmInvoice = function (id) {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'FATTURA PRENOTAZIONE',
            html: `Vuoi impostare lo stato della prenotazione <span class="bg-gray-200 dark:bg-gray-900 py-0.5 px-1">#${id}</span> su "Fatturata"?`,
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
                Livewire.dispatch('markAsInvoiced', { bookingId: id });
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

                                    <div id="penalty-preview-container" class="hidden mt-3 flex flex-col items-center justify-center min-h-[96px]">
                                        <div class="relative inline-block shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                                            <img id="penalty-img-preview" src="" class="hidden h-24 w-40 object-cover rounded-lg">
                                            <div id="penalty-pdf-preview" class="hidden flex flex-col items-center justify-center h-24 w-40 text-red-500 rounded-lg">
                                                <i class="fa-solid fa-file-pdf text-3xl mb-1"></i>
                                            </div>
                                        </div>
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

                                const fileInput = document.getElementById('penalty-file-input');

                                if (fileInput) {
                                    fileInput.addEventListener('change', function () {
                                        const file = this.files[0];
                                        const textEl = document.getElementById('file-chosen-text');
                                        const previewContainer = document.getElementById('penalty-preview-container');
                                        const imgPreview = document.getElementById('penalty-img-preview');
                                        const pdfPreview = document.getElementById('penalty-pdf-preview');

                                        if (file) {
                                            textEl.innerText = file.name;
                                            previewContainer.classList.remove('hidden');

                                            if (file.type.match('image.*')) {
                                                imgPreview.src = URL.createObjectURL(file);
                                                imgPreview.classList.remove('hidden');
                                                pdfPreview.classList.add('hidden');
                                            } else if (file.type === 'application/pdf') {
                                                imgPreview.classList.add('hidden');
                                                pdfPreview.classList.remove('hidden');
                                            }
                                        } else {
                                            textEl.innerText = 'Nessun file selezionato';
                                            previewContainer.classList.add('hidden');
                                        }
                                    });
                                }
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
                                    Swal.showValidationMessage('Il file è troppo grande! Il limite massimo è 8MB.');
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
                                    background: theme.background,
                                    color: theme.color,
                                    didOpen: (popup) => {
                                        popup.style.border = `2px solid ${theme.border}`;

                                        Swal.showLoading();

                                        const loader = popup.querySelector('.swal2-loader');
                                        if (loader) {
                                            loader.style.borderTopColor = '#d97706';
                                            loader.style.borderBottomColor = '#d97706';
                                        }
                                    },
                                    customClass: {
                                        popup: 'rounded-xl',
                                    }
                                });

                                const formData = new FormData();
                                formData.append('receipt', file);
                                formData.append('booking_id', bookingId);

                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                                fetch('/prenotazione/carica-contabile', {
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
                                            Swal.fire({
                                                title: 'Errore',
                                                text: 'Errore durante il caricamento del file.',
                                                icon: 'error',
                                                background: theme.background,
                                                color: theme.color,
                                                confirmButtonColor: '#d97706',
                                                didOpen: (popup) => { popup.style.border = `2px solid ${theme.border}`; },
                                                customClass: {
                                                    popup: 'rounded-xl',
                                                    confirmButton: 'text-md rounded-xl font-black uppercase tracking-widest px-3 py-2'
                                                }
                                            });
                                        }
                                    })
                                    .catch(() => {
                                        Swal.fire({
                                            title: 'Errore',
                                            text: errr || 'Impossibile connettersi al server per l\'upload.',
                                            icon: 'error',
                                            background: theme.background,
                                            color: theme.color,
                                            confirmButtonColor: '#d97706',
                                            didOpen: (popup) => { popup.style.border = `2px solid ${theme.border}`; },
                                            customClass: {
                                                popup: 'rounded-xl',
                                                confirmButton: 'text-md rounded-xl font-black uppercase tracking-widest px-3 py-2'
                                            }
                                        });
                                    });
                            }
                        });
                    }
                });
            }
        });
    };

    // CAMPER DELETE
    window.confirmCamperDeletion = function (component) {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'ELIMINARE IL CAMPER?',
            text: "Questa azione cancellerà definitivamente il mezzo, i prezzi, la scheda tecnica e tutte le immagini dal server. Non potrai tornare indietro!",
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
                component.call('deleteCamper');
            }
        });
    };
});