<?php

namespace App\Livewire\User;

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

    public function updateTime()
    {
        if (!$this->booking) return;

        $expiryTime = $this->booking->created_at->addMinutes(15);

        if (now()->greaterThanOrEqualTo($expiryTime) || $this->booking->status !== 'pending') {
            $this->booking = null;
            $this->timeLeft = null;

            session()->flash('error', 'Il tempo per il pagamento è scaduto.');
        } else {
            $this->timeLeft = gmdate("i:s", now()->diffInSeconds($expiryTime));
        }
    }

    public function render()
    {
        $formattedDates = '';
        if ($this->booking) {
            $start = \Carbon\Carbon::parse($this->booking->start_date)->format('d/m');
            $end = \Carbon\Carbon::parse($this->booking->end_date)->format('d/m/Y');
            $formattedDates = "dal $start al $end";
        }

        return view('livewire.user.payment-reminder', [
            'formattedDates' => $formattedDates
        ]);
    }
}
