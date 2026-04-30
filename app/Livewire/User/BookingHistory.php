<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;

class BookingHistory extends Component
{
    use WithPagination;

    public function render()
    {
        $bookings = auth()->user()->bookings()
            ->with('camper')
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->where('payment_status', 'paid')
            ->latest()
            ->paginate(10);

        return view('livewire.user.booking-history', [
            'bookings' => $bookings
        ]);
    }
}
