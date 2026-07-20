<?php

namespace App\Livewire\User;

use App\Models\Booking;
use Livewire\Component;

class UserNotification extends Component
{
    protected $listeners = ['booking-updated' => '$refresh'];

    // RENDER
    public function render()
    {
        if (!auth()->check() || auth()->user()->is_admin) {
            return view('livewire.user.user-notification', ['hasPending' => false]);
        }

        $user = auth()->user();

        $hasPending = Booking::where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('payment_status', 'unpaid')
                    ->orWhere('documents_status', 'pending')
                    ->orWhere('status', 'penalty_pending')
                    ->orWhereHas('damages', function ($q) {
                        $q->where('status', 'pending');
                    });
            })
            ->exists();

        return view('livewire.user.user-notification', [
            'hasPending' => $hasPending
        ]);
    }
}
