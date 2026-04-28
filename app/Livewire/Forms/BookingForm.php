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
            if (empty($value)) {
                $this->total_price = 0;
                $this->days_count = 0;
                $this->addError('date_range', 'Seleziona un intervallo di almeno 2 giorni.');
                return;
            }

            $this->splitDates($value);

            if ($this->start_date && $this->end_date) {
                $this->calculateBooking();

                $this->resetErrorBag(['date_range', 'days_count']);

                $this->validateOnly('days_count', [
                    'days_count' => 'integer|min:2'
                ], [
                    'days_count.min' => 'Il noleggio deve essere di almeno 2 giorni.'
                ]);
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
            ->whereNotIn('status', ['cancelled', 'expired'])
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

        if ($this->days_count < 2) {
            $this->total_price = 0;
            return;
        }

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

        $this->calculateBooking();

        $this->validate([
            'date_range' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'days_count' => 'required|integer|min:2',
            'total_price' => 'required|numeric|min:1',
        ], [
            'days_count.min' => 'Il noleggio minimo è di 2 giorni.',
            'total_price.min' => 'Errore nel calcolo del prezzo.',
            'start_date.after_or_equal' => 'La data di inizio non può essere nel passato.',
            'end_date.after' => 'La data di fine deve essere successiva a quella di inizio.',
        ]);

        $alreadyBooked = Booking::where('camper_id', $this->camper->id)
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->where(function ($q) {
                $q->where('payment_status', 'paid')
                    ->orWhere('created_at', '>=', now()->subMinutes(15));
            })
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                    ->orWhere(function ($sub) {
                        $sub->where('start_date', '<=', $this->start_date)
                            ->where('end_date', '>=', $this->end_date);
                    });
            })
            ->exists();

        if ($alreadyBooked) {
            $this->addError('date_range', 'Spiacente, queste date sono già state occupate.');
            return;
        }

        $booking = new Booking();

        $booking->user_id = auth()->id();
        $booking->customer_first_name = auth()->user()->first_name;
        $booking->customer_last_name = auth()->user()->last_name;
        $booking->customer_email = auth()->user()->email;
        $booking->customer_phone = auth()->user()->phone;
        $booking->camper_id = $this->camper->id;
        $booking->start_date = $this->start_date;
        $booking->end_date = $this->end_date;
        $this->calculateBooking();
        $booking->total_price = $this->total_price;
        $booking->status = 'pending';
        $booking->payment_status = 'unpaid';


        $booking->save();

        $this->reset(['date_range', 'start_date', 'end_date', 'total_price', 'days_count']);

        return redirect()->route('checkout', $booking);
    }

    public function render()
    {
        return view('livewire.forms.booking-form');
    }
}
