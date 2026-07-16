<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class BookingStats extends Component
{
    // STATS
    #[Computed]
    public function stats()
    {
        $data = Booking::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'cancellation_pending' THEN 1 ELSE 0 END) as cancellation_pending,
            SUM(CASE WHEN payment_status = 'penalty_pending' THEN 1 ELSE 0 END) as penalty_pending,
            SUM(CASE WHEN payment_status = 'penalty_verification' THEN 1 ELSE 0 END) as penalty_verification,
            SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN status = 'confirmed' THEN total_price ELSE 0 END) as earnings
        ")->first();

        $total = $data ? $data->toArray() : [
            'total' => 0,
            'pending' => 0,
            'cancellation_pending' => 0,
            'penalty_pending' => 0,
            'penalty_verification' => 0,
            'confirmed' => 0,
            'earnings' => 0
        ];

        $totalPending = ($total['pending'] ?? 0) + ($total['cancellation_pending'] ?? 0) + ($total['penalty_verification'] ?? 0);

        return [
            'counts' => $total,
            'totalPending' => $totalPending,
            'style' => [
                'border' => $totalPending > 0 ? 'border-amber-500' : 'border-green-500',
                'bg'     => $totalPending > 0 ? 'bg-amber-50 dark:bg-amber-900/20' : 'bg-green-50 dark:bg-green-900/20',
                'text'   => $totalPending > 0 ? 'text-amber-500' : 'text-green-500',
            ]
        ];
    }

    // UPDATE STATS
    #[On('booking-updated')]
    public function updateStats() {}

    // RENDER
    public function render()
    {
        return view('livewire.admin.booking-stats');
    }
}
