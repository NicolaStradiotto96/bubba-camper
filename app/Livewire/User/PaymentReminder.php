<?php

namespace App\Livewire\User;

use App\Models\Log;
use Carbon\Carbon;
use Livewire\Component;

class PaymentReminder extends Component
{
    public $booking;
    public $timeLeft;

    public function mount()
    {
        $this->booking = auth()->user()->bookings()
            ->where('payment_status', 'unpaid')
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(15))
            ->latest()
            ->first();

        $this->updateTime();
    }

    // UPDATE TIMER
    public function updateTime()
    {
        if (!$this->booking) return;

        $expiryTime = $this->booking->created_at->addMinutes(15);

        if (now()->greaterThanOrEqualTo($expiryTime) || $this->booking->status !== 'pending') {
            $this->booking->update(['status' => 'expired']);

            $this->logPaymentExpiration('booking_expired', "Prenotazione #{$this->booking->id} scaduta per mancato pagamento.", $this->booking);

            $this->booking = null;
            $this->timeLeft = null;

            $this->dispatch('swal-error', ['message' => 'Il tempo per il pagamento è scaduto. La prenotazione è stata annullata.']);
        } else {
            $this->timeLeft = gmdate("i:s", now()->diffInSeconds($expiryTime));
        }
    }

    // RENDER
    public function render()
    {
        $isPaying = auth()->user()->isPayingRightNow();

        $expiryTimestamp = $this->booking
            ? $this->booking->created_at->addMinutes(15)->timestamp
            : null;

        $formattedDates = '';
        if ($this->booking) {
            $start = Carbon::parse($this->booking->start_date)->format('d/m/Y');
            $end = Carbon::parse($this->booking->end_date)->format('d/m/Y');
            $formattedDates = "dal $start al $end";
        }

        return view('livewire.user.payment-reminder', [
            'isPaying' => $isPaying,
            'expiryTimestamp' => $expiryTimestamp,
            'formattedDates' => $formattedDates
        ]);
    }

    // LOG
    private function logPaymentExpiration(string $type, string $message, $booking)
    {
        Log::create([
            'type'       => $type,
            'message'    => $message,
            'context'    => [
                'booking_id' => $booking->id,
                'user_id'    => auth()->id(),
                'ip_address' => request()->ip(),
                'status'     => 'expired',
            ],
        ]);
    }
}
