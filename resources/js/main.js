/* TRANSITION DARK/LIGHT MODE */
const updateTheme = () => {
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

updateTheme();
document.addEventListener('livewire:navigated', updateTheme);

// FUNZIONE DI SUPPORTO PER INVIO FORM (ULID SAFE)
// main.js

document.addEventListener('livewire:init', () => {

    // 1. FUNZIONE CONFERMA
    window.confirmHostAction = function (id, name) {
        const isDark = document.documentElement.classList.contains('dark');

        Swal.fire({
            title: 'CONFERMA NOLEGGIO',
            html: `Vuoi confermare ufficialmente la prenotazione di <b class="text-amber-600 font-black uppercase">${name}</b>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'SÌ, CONFERMA',
            cancelButtonText: 'CHIUDI',
            confirmButtonColor: '#d97706',
            background: isDark ? '#1f2937' : '#ffffff',
            color: isDark ? '#ffffff' : '#111827',
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-5 py-3',
                cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-5 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // SPEDISCE L'EVENTO AL COMPONENTE PHP
                // Il nome della chiave (bookingId) deve essere uguale al nome del parametro nel PHP
                Livewire.dispatch('confirmBooking', { bookingId: id });
            }
        });
    };

    // 2. FUNZIONE RIMBORSO
    window.confirmRefundAction = function (id, amount, penalty, hasStripe) {
        const isDark = document.documentElement.classList.contains('dark');
        const bgColor = isDark ? '#1f2937' : '#ffffff';
        const textColor = isDark ? '#ffffff' : '#111827';

        Swal.fire({
            title: 'ANNULLAMENTO',
            html: `
                <div class="text-center space-y-4 px-2 mt-4">
                    <div class="p-3 bg-gray-100 dark:bg-gray-700/50 rounded-2xl border border-gray-200 dark:border-gray-600">
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Penale Calcolata</p>
                        <p class="text-xl font-black text-red-500">${penalty}%</p>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-2xl">
                        <p class="text-[10px] uppercase font-bold text-green-600 tracking-widest">Rimborso Dovuto</p>
                        <p class="text-2xl font-black text-green-600">${amount.toLocaleString('it-IT')}€</p>
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'PROCEDI',
            cancelButtonText: 'ESCI',
            confirmButtonColor: '#ef4444',
            background: bgColor,
            color: textColor,
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                if (amount > 0 && hasStripe) {
                    Swal.fire({
                        title: 'Metodo di Rimborso',
                        text: "Scegli come inviare il denaro al cliente:",
                        icon: 'question',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'STRIPE (Automatico)',
                        denyButtonText: 'MANUALE',
                        cancelButtonText: 'ANNULLA',
                        confirmButtonColor: '#059669',
                        denyButtonColor: '#374151',
                        background: bgColor,
                        color: textColor,
                        customClass: { popup: 'rounded-3xl' }
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
        const isDark = document.documentElement.classList.contains('dark');

        Swal.fire({
            title: 'REGISTRA SALDO',
            html: `Hai ricevuto il pagamento finale di <b class="text-green-600 text-xl">${balance.toLocaleString('it-IT')}€</b>?<br><small class="text-gray-500">La prenotazione verrà segnata come interamente pagata.</small>`,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'SÌ, SALDATO',
            cancelButtonText: 'ANNULLA',
            confirmButtonColor: '#059669',
            background: isDark ? '#1f2937' : '#ffffff',
            color: isDark ? '#ffffff' : '#111827',
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('markAsPaid', { bookingId: id });
            }
        });
    };
});