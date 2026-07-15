<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Component;

class AdminNotification extends Component
{
    protected $listeners = ['booking-updated' => '$refresh'];

    public function render()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return view('livewire.admin.admin-notification', ['hasPending' => false]);
        }

        $hasPending = Booking::whereIn('status', [
            'pending',
            'cancellation_pending',
            'penalty_verification'
        ])->exists();

        return view('livewire.admin.admin-notification', [
            'hasPending' => $hasPending
        ]);
    }
}
