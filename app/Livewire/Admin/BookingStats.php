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
            COALESCE(COUNT(*), 0) as total,
            COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as pending,
            COALESCE(SUM(CASE WHEN status = 'cancellation_pending' THEN 1 ELSE 0 END), 0) as cancellation_pending,
            COALESCE(SUM(CASE WHEN payment_status = 'penalty_pending' THEN 1 ELSE 0 END), 0) as penalty_pending,
            COALESCE(SUM(CASE WHEN payment_status = 'penalty_verification' THEN 1 ELSE 0 END), 0) as penalty_verification,
            COALESCE(SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END), 0) as confirmed,
            COALESCE(SUM(CASE WHEN status = 'confirmed' THEN total_price ELSE 0 END), 0) as earnings
        ")->first();

        $total = $data ? [
            'total' => $data->total ?? 0,
            'pending' => $data->pending ?? 0,
            'cancellation_pending' => $data->cancellation_pending ?? 0,
            'penalty_pending' => $data->penalty_pending ?? 0,
            'penalty_verification' => $data->penalty_verification ?? 0,
            'confirmed' => $data->confirmed ?? 0,
            'earnings' => $data->earnings ?? 0,
        ] : [
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
