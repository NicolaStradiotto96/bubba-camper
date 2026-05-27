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

        // Camper Description
        if (btnDesc) {
            const camperName = btnDesc.getAttribute('data-name');
            const camperDesc = btnDesc.getAttribute('data-description');
            const camperFullDesc = btnDesc.getAttribute('data-full-description');
            const theme = getSwalTheme();

            Swal.fire({
                title: '<i class="fa-solid fa-van-shuttle text-amber-500" style="font-size: 5rem;"></i> ',
                text: camperName,
                html: `
                    <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter text-center mb-5">
                        ${camperName}
                    </h2>
                    <div class="text-left text-base leading-relaxed px-1">
                        <p class="mb-3 text-amber-600 dark:text-amber-500 font-black text-center">
                            ${camperDesc}
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 font-black text-center mt-2">
                            ${camperFullDesc}
                        </p>
                    </div>
                `,
                confirmButtonText: 'CHIUDI',
                confirmButtonColor: '#d97706',
                background: theme.background,
                backdrop: theme.backdrop,
                color: theme.color,
                didOpen: (popup) => {
                    popup.style.border = `2px solid ${theme.border}`;
                    popup.style.width = '90%';
                    popup.style.maxWidth = '680px';
                },
                customClass: {
                    popup: 'rounded-xl shadow-2xl',
                    confirmButton: 'text-md rounded-xl font-black uppercase tracking-widest px-4 py-2'
                }
            });
        }
    });

    window.confirmHostAction = function (id, firstName, lastName, startDate, endDate) {
        const theme = getSwalTheme();

        // Confirm Booking
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

                    <div class="p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600">
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

    window.confirmRefundAction = function (id, amount, penalty, hasStripe) {
        const theme = getSwalTheme();

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
                        <p class="text-base uppercase text-gray-400 tracking-widest mb-1">Penale Calcolata</p>
                        <p class="text-lg text-green-500">${penalty}%</p>
                    </div>
                    <div class="p-3 bg-gray-100 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                        <p class="text-base uppercase text-gray-400 tracking-widest mb-1">Rimborso Dovuto</p>
                        <p class="text-lg text-red-500">${amount.toLocaleString('it-IT')}€</p>
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
                    }).then((res) => {
                        if (res.isConfirmed) submitRefund(id, "1");
                        else if (res.isDenied) submitRefund(id, "0");
                    });
                } else {
                    submitRefund(id, "0");
                }
            }
        });
    };

    function submitRefund(id, useStripe) {
        const form = document.getElementById(`refund-form-${id}`);
        const input = document.getElementById(`stripe-input-${id}`);
        if (form && input) {
            input.value = useStripe;
            form.submit();
        }
    }

    window.confirmPayment = function (id, balance) {
        const theme = getSwalTheme();

        // Complete Booking
        Swal.fire({
            title: 'REGISTRA SALDO',
            html: `Hai ricevuto il pagamento finale di <b class="text-green-500 text-xl">${balance.toLocaleString('it-IT')}€</b>?<br><small class="text-gray-500">La prenotazione verrà segnata come interamente pagata.</small>`,
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
});