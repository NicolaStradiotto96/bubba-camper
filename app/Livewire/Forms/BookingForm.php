<?php

namespace App\Livewire\Forms;

use App\Models\Booking;
use App\Models\Camper;
use Carbon\Carbon;
use Livewire\Component;

class BookingForm extends Component
{
    public Camper $camper;
    public $date_range;
    public $start_date;
    public $end_date;
    public $total_price = 0;
    public $days_count = 0;

    public function mount(Camper $camper)
    {
        $this->camper = $camper;
    }

    public function updated($propertyName, $value)
    {
        if ($propertyName === 'date_range') {
            $this->splitDates($value);

            if ($this->start_date && $this->end_date) {
                $this->calculateBooking();
            } else {
                $this->total_price = 0;
                $this->days_count = 0;
            }
        }
    }

    protected function splitDates($value)
    {
        $separator = ' al ';

        if (!empty($value) && str_contains($value, $separator)) {
            $dates = explode($separator, $value);
            try {
                $this->start_date = Carbon::createFromFormat('Y-m-d', trim($dates[0]))->format('Y-m-d');
                $this->end_date = Carbon::createFromFormat('Y-m-d', trim($dates[1]))->format('Y-m-d');
            } catch (\Exception $e) {
                $this->start_date = null;
                $this->end_date = null;
            }
        }
    }
    public function getBookedDatesProperty()
    {
        return Booking::where('camper_id', $this->camper->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) {
                $query->where('payment_status', 'paid')
                    ->orWhere('created_at', '>=', now()->subMinutes(15));
            })
            ->get(['start_date', 'end_date'])
            ->flatMap(function ($booking) {
                $period = new \DatePeriod(
                    Carbon::parse($booking->start_date),
                    new \DateInterval('P1D'),
                    Carbon::parse($booking->end_date)->addDay()
                );
                $dates = [];
                foreach ($period as $date) {
                    $dates[] = $date->format('Y-m-d');
                }
                return $dates;
            })->toArray();
    }

    protected function calculateBooking()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $this->days_count = $start->diffInDays($end) + 1;

        $total = 0;
        $tempDate = $start->copy();

        while ($tempDate <= $end) {
            $total += $this->getDayPrice($tempDate);
            $tempDate->addDay();
        }

        $this->total_price = $total;
    }

    protected function getDayPrice($date)
    {
        $month = $date->month;
        if (in_array($month, [7, 8])) return 140;
        if (in_array($month, [4, 5, 6, 9, 10])) return 120;
        return 100;
    }

    public function saveBooking()
    {
        if (!$this->start_date || !$this->end_date) {
            $this->addError('date_range', 'Seleziona un intervallo di date valido.');
            return;
        }

        $alreadyBooked = Booking::where('camper_id', $this->camper->id)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                    ->orWhere(function ($q) {
                        $q->where('start_date', '<=', $this->start_date)
                            ->where('end_date', '>=', $this->end_date);
                    });
            })
            ->exists();

        if ($alreadyBooked) {
            $this->addError('date_range', 'Spiacente, queste date sono già state occupate.');
            return;
        }

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'customer_first_name' => auth()->user()->first_name,
            'customer_last_name' => auth()->user()->last_name,
            'customer_email' => auth()->user()->email,
            'customer_phone' => auth()->user()->phone,
            'camper_id' => $this->camper->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_price' => $this->total_price,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $this->reset(['date_range', 'start_date', 'end_date', 'total_price', 'days_count']);

        return redirect()->route('checkout', ['booking' => $booking->id]);
    }

    public function render()
    {
        return view('livewire.forms.booking-form');
    }
}
