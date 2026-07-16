// TRANSITION DARK/LIGHT MODE
const updateTheme = () => {
    if (localStorage.getItem('color-theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
};

updateTheme();

document.addEventListener('livewire:navigated', updateTheme);

// ALPINE CUSTOM CLASSES
document.addEventListener('alpine:init', () => {
    Alpine.directive('numbers', (el) => {
        el.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    });

    Alpine.directive('price', (el) => {
        el.addEventListener('input', (e) => {
            let val = e.target.value;
            val = val.replace(/[^0-9.,]/g, '');
            val = val.replace(',', '.');
            const parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts.slice(1).join('');
            }
            e.target.value = val;
        });
    });
});

// THEME TOGGLER
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

// CUSTOM THEME
const getBaseSwal = () => {
    const theme = getSwalTheme();
    return {
        background: theme.background,
        color: theme.color,
        didOpen: (popup) => { popup.style.border = `2px solid ${theme.border}`; },
        customClass: { popup: 'rounded-[2rem]', confirmButton: 'text-md rounded-[2rem] font-black uppercase tracking-widest px-3 py-2', denyButton: 'text-md rounded-[2rem] font-black uppercase tracking-widest px-3 py-2', cancelButton: 'text-md rounded-[2rem] font-black uppercase tracking-widest px-3 py-2' }
    };
};

// FETCH
async function safeFetch(url, options = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const headers = options.body instanceof FormData
        ? { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
        : { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken };

    const response = await fetch(url, {
        ...options,
        headers: { ...headers, ...options.headers }
    });

    if (!response.ok) {
        const errorData = await response.json().catch(() => ({}));
        throw new Error(errorData.message || `Errore HTTP: ${response.status}`);
    }

    return await response.json();
}

// PAY PENALTY
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const bookingId = urlParams.get('pay_penalty');

    if (bookingId) {
        safeFetch(`/prenotazione/${bookingId}/pagamento-penale`)
            .then(data => {
                if (data.status === 'penalty_pending') {
                    window.payPenaltyAction(bookingId, data.amount);
                } else {
                    const theme = getSwalTheme();

                    Swal.fire({
                        icon: 'error',
                        iconColor: '#ef4444',
                        title: 'ERRORE',
                        text: 'Nessuna penale trovata per la prenotazione selezionata.',
                        confirmButtonText: 'CHIUDI',
                        confirmButtonColor: '#d97706',
                        ...getBaseSwal(),
                    });
                }
            })
            .catch(err => {
                console.error('Errore durante il recupero penale:', err);
            })
            .finally(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
    }
});

// SWEETALERT 2
document.addEventListener('livewire:init', () => {

    // UTILITIES
    window.triggerError = function (message) {
        window.dispatchEvent(new CustomEvent('swal-error', {
            detail: [{ message: message }]
        }));
    };

    window.triggerSuccess = function (message) {
        window.dispatchEvent(new CustomEvent('swal-success', {
            detail: [{ message: message }]
        }));
    };

    // SUCCESS MESSAGE
    window.addEventListener('swal-success', event => {
        const theme = getSwalTheme();
        const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;

        Swal.fire({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
            icon: 'success',
            iconColor: '#1fae53',
            title: 'OPERAZIONE COMPLETATA',
            text: data.message,
            background: theme.background,
            color: theme.color,
            didOpen: (toast) => {
                toast.style.marginTop = '80px';
                toast.style.border = `2px solid #1fae53`;
            },
            customClass: {
                popup: 'rounded-[2rem]'
            }
        });
    });

    // ERROR MESSAGE
    window.addEventListener('swal-error', event => {
        const theme = getSwalTheme();
        const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;

        Swal.fire({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
            icon: 'error',
            iconColor: '#ef4444',
            title: 'ERRORE',
            text: data.message,
            background: theme.background,
            color: theme.color,
            didOpen: (toast) => {
                toast.style.marginTop = '80px';
                toast.style.border = `2px solid #ef4444`;
            },
            customClass: {
                popup: 'rounded-[2rem]'
            }
        });
    });

    // BOOKING NOT FOUND
    window.addEventListener('swal-modal-error', () => window.showErrorSwal());
    window.showErrorSwal = () => window.showSwalError('Prenotazione non trovata.');
    window.docsErrorSwal = () => window.showSwalError('Documenti già caricati.');

    window.showSwalError = function (message) {
        const theme = getSwalTheme();

        Swal.fire({
            icon: 'error',
            iconColor: '#ef4444',
            title: 'ERRORE',
            text: message,
            confirmButtonText: 'CHIUDI',
            confirmButtonColor: '#d97706',
            ...getBaseSwal(),
        });
    };

    window.openBookingModal = function (id, $wire, $dispatch) {
        $wire.checkBookingAccess(id).then(response => {
            if (!response.authorized) {
                window.showErrorSwal();
            } else if (!response.needsDocs) {
                window.docsErrorSwal();
            } else {
                $dispatch('open-doc-modal', { id: id });
            }
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    };

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
    window.confirmRefundAction = function (id, totalPaid, penaltyPercent, penaltyAmount, hasStripe) {
        const theme = getSwalTheme();
        const totalPaidNum = parseFloat(totalPaid);
        const penaltyNum = parseFloat(penaltyAmount);

        const askRefundMethod = (applyPenalty, byAdmin) => {
            const finalRefund = applyPenalty
                ? Math.max(0, totalPaidNum - penaltyNum)
                : totalPaidNum;

            if (finalRefund > 0) {
                Swal.fire({
                    title: 'METODO DI RIMBORSO',
                    html: `
                        <div class="text-center space-y-4 px-2 font-black">
                            <p class="text-base text-gray-800 dark:text-gray-300 mb-2 px-2 tracking-wide">
                                Scegli come rimborsare il denaro al cliente
                            </p>
                            <div class="p-3 bg-gray-100 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                                <p class="text-base uppercase text-gray-400 tracking-widest mb-1">Rimborso Dovuto</p>
                                <p class="text-lg text-red-500">${finalRefund}€</p>
                            </div>
                        </div>
                    `,
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
                    if (result.isConfirmed) {
                        Livewire.dispatch('cancelBooking', {
                            bookingId: id,
                            applyPenalty: applyPenalty,
                            byAdmin: byAdmin,
                            useStripe: true
                        });
                    } else if (result.isDenied) {
                        Swal.fire({
                            title: 'RIMBORSO CLIENTE',
                            html: `
                                <div class="text-center space-y-3 px-2 font-black">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Per completare il rimborso manuale, allega la contabile del bonifico:
                                    </p>
                                                        
                                    <input type="file" id="refund-file-input" accept=".pdf,.png,.jpg,.jpeg" class="hidden" 
                                        onchange="document.getElementById('refund-file-chosen-text').innerText = this.files[0] ? this.files[0].name : 'Nessun file selezionato'" />
                                                                
                                    <div class="flex flex-col items-center justify-center gap-2 mt-2">
                                        <button type="button" 
                                            onclick="document.getElementById('refund-file-input').click()"
                                            class="inline-flex items-center gap-2 bg-amber-600 text-white text-xs font-black uppercase tracking-widest py-2 px-4 rounded-xl transition shadow-md cursor-pointer hover:bg-amber-700">
                                            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                            Sfoglia...
                                        </button>
                                        <span id="refund-file-chosen-text" class="text-xs text-gray-400 font-sans italic tracking-wide">
                                            Nessun file selezionato
                                        </span>
                                    </div>
                                    
                                    <div class="mt-3 border border-transparent min-h-[100px]">
                                        <div id="refund-preview-container" class="hidden flex flex-col items-center justify-center min-h-[96px]">
                                            <div class="relative inline-block shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                                                <img id="refund-img-preview" src="" class="hidden h-24 w-40 object-cover rounded-lg">
                                                <div id="refund-pdf-preview" class="hidden flex flex-col items-center justify-center h-24 w-40 text-red-500 rounded-lg">
                                                    <i class="fa-solid fa-file-pdf text-3xl mb-1"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `,
                            icon: 'info',
                            iconColor: '#d97706',
                            showCancelButton: true,
                            confirmButtonText: 'INVIA',
                            confirmButtonColor: '#d97706',
                            cancelButtonText: 'CHIUDI',
                            background: theme.background,
                            color: theme.color,
                            didOpen: (popup) => {
                                popup.style.border = `2px solid ${theme.border}`;

                                const fileInput = document.getElementById('refund-file-input');

                                if (fileInput) {
                                    fileInput.addEventListener('change', function () {
                                        const file = this.files[0];
                                        const textEl = document.getElementById('refund-file-chosen-text');
                                        const previewContainer = document.getElementById('refund-preview-container');
                                        const imgPreview = document.getElementById('refund-img-preview');
                                        const pdfPreview = document.getElementById('refund-pdf-preview');

                                        if (previewContainer) previewContainer.classList.add('hidden');
                                        if (imgPreview) imgPreview.classList.add('hidden');
                                        if (pdfPreview) pdfPreview.classList.add('hidden');
                                        if (textEl) textEl.innerText = 'Nessun file selezionato';

                                        if (file) {
                                            if (textEl) textEl.innerText = file.name;
                                            if (previewContainer) previewContainer.classList.remove('hidden');

                                            if (file.type.match('image.*')) {
                                                if (imgPreview) {
                                                    imgPreview.src = URL.createObjectURL(file);
                                                    imgPreview.classList.remove('hidden');
                                                }
                                            } else if (file.type === 'application/pdf') {
                                                if (pdfPreview) pdfPreview.classList.remove('hidden');
                                            }
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
                                const fileInput = document.getElementById('refund-file-input');
                                const file = fileInput.files[0];
                                const maxSize = 5 * 1024 * 1024;
                                const allowedTypes = [
                                    'application/pdf',
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png'
                                ];

                                if (!file) {
                                    Swal.showValidationMessage('Devi selezionare un file prima di inviare!');
                                    return false;
                                }

                                if (file.size > maxSize) {
                                    Swal.showValidationMessage('Il file è troppo grande! Il limite massimo è 5MB.');
                                    return false;
                                }

                                if (!allowedTypes.includes(file.type)) {
                                    Swal.showValidationMessage('File non valido. Carica un file PDF, JPG, JPEG o PNG.');
                                    return false;
                                }

                                return file;
                            }
                        }).then((fileResult) => {
                            if (fileResult.isConfirmed) {
                                const formData = new FormData();
                                formData.append('receipt', fileResult.value);
                                formData.append('booking_id', id);
                                formData.append('type', 'refund');

                                safeFetch('/prenotazione/carica-contabile', {
                                    method: 'POST',
                                    body: formData
                                })
                                    .then(() => {
                                        Livewire.dispatch('cancelBooking', {
                                            bookingId: id,
                                            applyPenalty: applyPenalty,
                                            byAdmin: byAdmin,
                                            useStripe: false
                                        });
                                    })
                                    .catch(err => {
                                        console.error('Errore caricamento contabile:', err);
                                        window.triggerError(err.message || 'Si è verificato un errore imprevisto.');
                                    });
                            }
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
                        <p class="text-base uppercase text-gray-400 tracking-widest mb-1">Penale Calcolata (${penaltyPercent}%)</p>
                        <p class="text-lg text-green-500">${penaltyAmount}€</p>
                    </div>
                </div>
            `,
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'CON PENALE',
            confirmButtonColor: '#1fae53',
            denyButtonText: 'SENZA PENALE',
            denyButtonColor: '#ef4444',
            cancelButtonText: 'CHIUDI',
            background: theme.background,
            color: theme.color,
            input: 'checkbox',
            inputValue: 0,
            inputPlaceholder: 'Confermo di voler annullare la prenotazione.',
            inputValidator: (result) => {
                return !result && 'Devi accettare per poter procedere';
            },
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

    // Complete Damage
    window.confirmDamageResolution = function (damageId, amount) {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'REGISTRA SALDO',
            html: `Hai ricevuto il pagamento di <b class="text-green-500 text-xl">${amount}</b>?<br>L'azione chiuderà la pratica del danno.`,
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
                Livewire.dispatch('confirmDamageResolution', { damageId: damageId });
            }
        });
    };

    // Invoice
    window.confirmInvoice = function (id) {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'FATTURA PRENOTAZIONE',
            html: `Vuoi impostare lo stato della prenotazione <span class="id">#${id}</span> su "Fatturata"?`,
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
                                    class="id">#${bookingId}</span>?`;

        if (penaltyAmount > 0) {
            message += `<br><br><span class="text-amber-500 font-bold">Attenzione:</span> In base alle tempistiche contrattuali, è prevista una penale di trattenuta pari a <b>${penaltyAmount}€</b>.`;
        } else {
            message += `<br><br>La tua richiesta sarà presa in carico. Il nostro staff la valuterà e ti darà una risposta il prima possibile.`;
        }

        message += `<br><br><div class="text-sm italic text-gray-500">Questa operazione non potrà essere annullata.</div>`;

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
            input: 'checkbox',
            inputValue: 0,
            inputPlaceholder: 'Confermo di voler annullare la mia prenotazione.',
            inputValidator: (result) => {
                return !result && 'Devi accettare per poter procedere';
            },
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

    // Pay Penalty/Damage
    window.payPenaltyAction = function (bookingId, penaltyAmount, type, damageId = null) {
        const theme = getSwalTheme();

        const isDamage = type === 'damages';
        const title = isDamage ? 'PAGAMENTO DANNI' : 'PAGAMENTO PENALE';
        const description = isDamage
            ? `Per regolarizzare la situazione danni della prenotazione <span class="id">#${bookingId}</span>, è necessario corrispondere:`
            : `Per completare l'annullamento e regolarizzare la prenotazione <span class="id">#${bookingId}</span>, è necessario corrispondere la penale contrattuale pari a:`;

        const label = isDamage ? 'Importo danni' : 'Importo da pagare';

        Swal.fire({
            title: title,
            html: `
                <div class="text-center space-y-4 px-2 font-black">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ${description}
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
                        Livewire.dispatch('processPenaltyPayment', { bookingId: bookingId, type: type, damageId: damageId });
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

                                    <div class="mt-3 border border-transparent min-h-[100px]">
                                        <div id="penalty-preview-container" class="hidden flex flex-col items-center justify-center min-h-[96px]">
                                            <div class="relative inline-block shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                                                <img id="penalty-img-preview" src="" class="hidden h-24 w-40 object-cover rounded-lg">
                                                <div id="penalty-pdf-preview" class="hidden flex flex-col items-center justify-center h-24 w-40 text-red-500 rounded-lg">
                                                    <i class="fa-solid fa-file-pdf text-3xl mb-1"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `,
                            icon: 'info',
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

                                        previewContainer.classList.add('hidden');
                                        imgPreview.classList.add('hidden');
                                        pdfPreview.classList.add('hidden');
                                        textEl.innerText = 'Nessun file selezionato';

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
                                const file = fileInput.files[0];
                                const maxSize = 5 * 1024 * 1024;
                                const allowedTypes = [
                                    'application/pdf',
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png'
                                ];

                                if (!file) {
                                    Swal.showValidationMessage('Devi selezionare un file prima di inviare!');
                                    return false;
                                }

                                if (file.size > maxSize) {
                                    Swal.showValidationMessage('Il file è troppo grande! Il limite massimo è 5MB.');
                                    return false;
                                }

                                if (!allowedTypes.includes(file.type)) {
                                    Swal.showValidationMessage('File non valido. Carica un file PDF, JPG, JPEG o PNG.');
                                    return false;
                                }

                                return file;
                            }
                        }).then((fileResult) => {
                            if (fileResult.isConfirmed && fileResult.value) {
                                const formData = new FormData();
                                formData.append('receipt', fileResult.value);
                                formData.append('booking_id', bookingId);
                                formData.append('type', type);
                                if (type === 'damages') {
                                    formData.append('damage_id', damageId);
                                }

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
                                            const payload = { bookingId: bookingId, type: type };
                                            if (type === 'damages') {
                                                payload.damageId = damageId;
                                            }
                                            Livewire.dispatch('processPenaltyBankTransfer', payload);
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

    // REJECT RECIEPT
    window.rejectReceiptAction = function (bookingId, type, damageId = null) {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'RIFIUTA CONTABILE',
            input: 'textarea',
            inputLabel: 'Motivazione del rifiuto',
            inputPlaceholder: 'Inserisci il motivo per cui la ricevuta non è valida...',
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
                denyButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2',
                cancelButton: 'text-md rounded-xl font-black tracking-widest px-3 py-2'
            },
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Devi inserire una motivazione!');
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                safeFetch('/prenotazione/rifiuta-contabile', {
                    method: 'POST',
                    body: JSON.stringify({
                        booking_id: bookingId,
                        type: type,
                        damage_id: damageId,
                        reason: result.value
                    })
                }).then(() => {
                    Livewire.dispatch('refresh-page');
                }).catch(err => {
                    console.error('Errore durante il rifiuto contabile:', err);
                });
            }
        });
    }

    // CONFIRM DELETE
    window.confirmAction = function (id, title, text, eventName) {
        const theme = getSwalTheme();

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'PROCEDI',
            cancelButtonText: 'CHIUDI',
            ...getBaseSwal(),
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch(eventName, { id: id });
            }
        });
    }
});