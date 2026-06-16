<?php

namespace App\Models;

use App\Models\Camper;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Booking extends Model
{
    use HasUlids;

    protected $fillable = [
        'start_date',
        'end_date',
        'total_price',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'camper_id',
        'status',
        'terms_accepted',
        'privacy_accepted',
        'terms_and_privacy_accepted_at',
        'terms_and_privacy_accepted_ip',
        'contract_version',
        'driver_license_path',
        'id_card_path',
        'documents_status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'balance_payment' => 'decimal:2',
        'cancellation_requested_at' => 'datetime',
        'cancellation_confirmed_at' => 'datetime',
        'terms_accepted' => 'boolean',
        'privacy_accepted' => 'boolean',
        'terms_and_privacy_accepted_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'ulid';
    }

    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    protected static function booted()
    {
        static::creating(function ($booking) {
            $booking->down_payment = $booking->total_price * 0.30;
            $booking->balance_payment = $booking->total_price * 0.70;
        });
    }

    public function calculateExpectedRefund()
    {
        $today = now()->startOfDay();
        $startDate = \Carbon\Carbon::parse($this->start_date)->startOfDay();
        $daysToTrip = $today->diffInDays($startDate, false);

        if ($daysToTrip < 10) {
            $penaltyPercent = 1.0;
        } elseif ($daysToTrip < 30) {
            $penaltyPercent = 0.8;
        } elseif ($daysToTrip < 60) {
            $penaltyPercent = 0.5;
        } else {
            $penaltyPercent = 0.1;
        }

        $totalPenaltyAmount = $this->total_price * $penaltyPercent;

        if (in_array($this->payment_status, ['fully_paid', 'refunded_stripe', 'refunded_manual'])) {
            $totalAmountPaid = $this->total_price;
        } else {
            $totalAmountPaid = $this->down_payment ?? 0;
        }

        $actualRefund = max(0, $totalAmountPaid - $totalPenaltyAmount);

        $remainingPenalty = 0;
        if ($totalPenaltyAmount > $totalAmountPaid) {
            $remainingPenalty = $totalPenaltyAmount - $totalAmountPaid;
        }

        return [
            'refund_amount' => $actualRefund,
            'penalty_amount' => $totalPenaltyAmount,
            'remaining_penalty' => $remainingPenalty,
            'days' => $daysToTrip,
            'penalty_percent' => $penaltyPercent * 100
        ];
    }

    public function isFullyPaid(): bool
    {
        return $this->payment_status === 'fully_paid';
    }

    public function canBeConfirmed(): bool
    {
        return $this->status === 'pending' && $this->payment_status === 'paid';
    }

    public function canBeCompleted(): bool
    {
        return $this->status === 'confirmed' && $this->payment_status === 'paid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function camper(): BelongsTo
    {
        return $this->belongsTo(Camper::class);
    }
}
