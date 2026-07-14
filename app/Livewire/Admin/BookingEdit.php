<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Maintenance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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

    // IS ADMIN?
    public function mount(Booking $booking)
    {
        if (!auth()->user()?->is_admin) {
            abort(403);
        }

        $this->booking = $booking;
        $this->camper_id = $booking->camper_id;
        $this->editingId = $booking->id;
        $this->start_date = $booking->start_date->format('d-m-Y');
        $this->end_date = $booking->end_date->format('d-m-Y');
        $this->new_total_price = $booking->total_price;
    }

    // EDIT BOOKING
    public function updateDates()
    {
        $this->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        foreach (CarbonPeriod::create($start, $end) as $date) {
            if (in_array($date->format('d-m-Y'), $this->getBookedDatesProperty())) {
                return $this->addError('start_date', 'Il periodo selezionato include date già occupate.');
            }
        }

        $newTotal = $this->calculateNewTotal() ?? $this->new_total_price;

        $this->booking->update([
            'start_date'      => $start,
            'end_date'        => $end,
            'total_price'     => $newTotal,
            'balance_payment' => $newTotal - $this->booking->down_payment,
            'balance_paid'    => false,
            'payment_status'  => 'paid',
        ]);

        session()->flash('swal-success', "Prenotazione #{$this->booking->id} aggiornata con successo!");
        return $this->redirect(route('dashboard'), navigate: true);
    }

    // CALENDAR
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

        $maintenances = Maintenance::where('camper_id', $this->camper_id)
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
        $blocked = $this->getBookedDatesProperty();

        $period = new \DatePeriod(
            Carbon::parse($this->start_date),
            new \DateInterval('P1D'),
            Carbon::parse($this->end_date)->addDay()
        );

        foreach ($period as $date) {
            if (in_array($date->format('d-m-Y'), $blocked)) {
                return false;
            }
        }

        return true;
    }

    // CALCULATE PRICE
    public function calculatePriceForRange($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $camper = $this->booking->camper;

        $total = 0;

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $total += $camper->getPriceForDate($date);
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

    // UPDATE CALENDAR
    public function updated($propertyName)
    {
        if ($propertyName === 'start_date' || $propertyName === 'end_date') {
            $this->calculateNewTotal();
        }
    }

    // RENDER
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.booking-edit');
    }
}
