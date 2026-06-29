<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Maintenance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class BookingEdit extends Component
{
    public Booking $booking;
    public $camper_id;
    public $start_date;
    public $end_date;
    public $editingId = null;
    public $new_total_price;


    public function mount(Booking $booking)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $this->booking = $booking;
        $this->camper_id = $booking->camper_id;
        $this->editingId = $booking->id;
        $this->start_date = $booking->start_date->format('d-m-Y');
        $this->end_date = $booking->end_date->format('d-m-Y');
        $this->new_total_price = $booking->total_price;
    }

    public function getBookedDatesProperty()
    {
        if (!$this->camper_id) {
            return [];
        }

        $limitDate = now()->addMonths(12);

        $bookings = Booking::query()
            ->where('camper_id', $this->camper_id)
            ->where('id', '!=', $this->editingId)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where('start_date', '<=', $limitDate)
            ->where(function ($query) {
                $query->where('payment_status', 'paid')
                    ->orWhere('created_at', '>=', now()->subMinutes(15));
            })
            ->get(['start_date', 'end_date']);

        $maintenances = \App\Models\Maintenance::where('camper_id', $this->camper_id)
            ->where('start_date', '<=', $limitDate)
            ->when($this->editingId, function ($query) {
                $query->where('id', '!=', $this->editingId);
            })
            ->get(['start_date', 'end_date']);

        return $bookings->concat($maintenances)
            ->flatMap(function ($item) {
                $isBooking = $item instanceof Booking;

                $start = Carbon::parse($item->start_date);
                $end = Carbon::parse($item->end_date);

                $extendedStart = $isBooking ? $start->copy()->subDay() : $start->copy();
                $extendedEnd = $isBooking ? $end->copy()->addDays(2) : $end->copy()->addDay();

                $period = new \DatePeriod(
                    $extendedStart,
                    new \DateInterval('P1D'),
                    $extendedEnd
                );

                $dates = [];
                foreach ($period as $date) {
                    $dates[] = $date->format('d-m-Y');
                }
                return $dates;
            })
            ->unique()
            ->values()
            ->toArray();
    }

    public function validateRange()
    {
        if (!$this->start_date || !$this->end_date) return false;

        $start = Carbon::parse($this->start_date)->format('Y-m-d');
        $end = Carbon::parse($this->end_date)->format('Y-m-d');

        $isBooked = Booking::where('camper_id', $this->camper_id)
            ->where('id', '!=', $this->booking->id)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                    });
            })->exists();

        $isMaintained = Maintenance::where('camper_id', $this->camper_id)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                    });
            })->exists();

        return !$isBooked && !$isMaintained;
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'start_date' || $propertyName === 'end_date') {
            $this->calculateNewTotal();
        }
    }

    public function calculatePriceForRange($startDate, $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        $total = 0;
        $current = $start->copy();

        while ($current <= $end) {
            $month = $current->month;
            if (in_array($month, [7, 8])) {
                $total += $this->price_high ?? 140;
            } elseif (in_array($month, [4, 5, 6, 9, 10])) {
                $total += $this->price_medium ?? 120;
            } else {
                $total += $this->price_low ?? 100;
            }
            $current->addDay();
        }

        return $total;
    }

    public function calculateNewTotal()
    {
        if ($this->start_date && $this->end_date) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);

            $this->new_total_price = $this->calculatePriceForRange($start, $end);
        }
    }

    public function updateDates()
    {
        $this->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        if (!$this->validateRange()) {
            return $this->addError('start_date', 'Il periodo selezionato include date già occupate.');
        }

        $newTotal = $this->calculatePriceForRange($this->start_date, $this->end_date);

        $newBalance = $newTotal - $this->booking->down_payment;

        $this->booking->start_date = Carbon::parse($this->start_date);
        $this->booking->end_date = Carbon::parse($this->end_date);
        $this->booking->total_price = $newTotal;
        $this->booking->balance_payment = $newBalance;
        $this->booking->balance_paid = false;
        $this->booking->payment_status = 'paid';
        $this->booking->save();

        session()->flash('success', 'Prenotazione aggiornata con successo!');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.booking-edit');
    }
}
