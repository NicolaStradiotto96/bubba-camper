<?php

namespace App\Models;

use App\Models\Camper;
use App\Models\Damage;
use App\Models\Log;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Booking extends Model
{
    use HasUlids;

    protected $fillable = [
        'start_date',
        'end_date',
        'total_price',
        'down_payment',
        'balance_payment',
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
        'driver_license_front_path',
        'driver_license_back_path',
        'id_card_front_path',
        'id_card_back_path',
        'documents_status',
        'refund_receipt_path',
        'refund_paid_at',
        'penalty_receipt_path',
        'penalty_paid_at',
        'user_id',
        'down_paid',
        'down_paid_at',
        'balance_paid',
        'balance_paid_at',
        'payment_status',
        'stripe_payment_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'balance_payment' => 'decimal:2',
        'terms_accepted' => 'boolean',
        'privacy_accepted' => 'boolean',
        'down_paid' => 'boolean',
        'balance_paid' => 'boolean',
        'terms_and_privacy_accepted_at' => 'datetime',
        'cancellation_requested_at' => 'datetime',
        'cancellation_confirmed_at' => 'datetime',
        'refund_paid_at' => 'datetime',
        'penalty_paid_at' => 'datetime',
        'down_paid_at' => 'datetime',
        'balance_paid_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'id';
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

    public static function getExcludedStatuses()
    {
        return ['cancelled', 'cancelled_by_admin', 'expired'];
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

        if ($this->status === 'cancelled_by_admin') {
            $totalPenaltyAmount = 0;
        } else {
            $totalPenaltyAmount = $this->total_price * $penaltyPercent;
        }

        $totalAmountPaid = 0;
        if ($this->down_paid) {
            $totalAmountPaid += $this->down_payment;
        }
        if ($this->balance_paid) {
            $totalAmountPaid += $this->balance_payment;
        }

        $actualRefund = max(0, $totalAmountPaid - $totalPenaltyAmount);

        $remainingPenalty = max(0, $totalPenaltyAmount - $totalAmountPaid);

        return [
            'refund_amount' => $actualRefund,
            'total_paid' => $totalAmountPaid,
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

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function damages(): HasMany
    {
        return $this->hasMany(Damage::class);
    }
}
