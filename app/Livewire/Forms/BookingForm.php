<?php

namespace App\Livewire\Forms;

use App\Models\Booking;
use App\Models\Camper;
use Carbon\Carbon;
use Livewire\Component;

class BookingForm extends Component
{
    public Camper $camper;
    public $dateRange;
    public $startDate;
    public $endDate;
    public $totalDays = 0;
    public $totalPrice = 0;

    public function mount(Camper $camper)
    {
        $this->camper = $camper;
    }

    public function updatedDateRange($value)
    {
        if (str_contains($value, ' to ')) {
            $dates = explode(' to ', $value);
            $this->startDate = Carbon::parse($dates[0]);
            $this->endDate = Carbon::parse($dates[1]);

            $this->totalDays = $this->startDate->diffInDays($this->endDate);
            if ($this->totalDays == 0) $this->totalDays = 1;

            $this->totalPrice = $this->totalDays * $this->camper->price_per_day;
        }
    }

    public function saveBooking()
    {
        $alreadyBooked = Booking::where('camper_id', $this->camper->id)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->startDate, $this->endDate])
                    ->orWhereBetween('end_date', [$this->startDate, $this->endDate]);
            })->exists();

        if ($alreadyBooked) {
            $this->addError('dateRange', 'Spiacente, il camper è già prenotato per queste date.');
            return;
        }
        // Validation/Payment Here
        session()->flash('message', 'Date selezionate con successo! Totale: ' . $this->totalPrice . '€');
    }

    public function render()
    {
        return view('livewire.forms.booking-form');
    }
}
