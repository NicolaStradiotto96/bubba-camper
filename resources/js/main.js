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