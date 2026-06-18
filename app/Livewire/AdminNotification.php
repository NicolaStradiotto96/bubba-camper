<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Component;

class AdminNotification extends Component
{
    protected $listeners = ['booking-updated' => '$refresh'];

    public function render()
    {
        $hasPending = false;

        if (auth()->check() && auth()->user()->is_admin) {
            $hasPending = Booking::where('status', ['pending', 'cancellation_pending', 'penalty_verification'])->exists();
        }

        return view('livewire.admin-notification', [
            'hasPending' => $hasPending
        ]);
    }
}
