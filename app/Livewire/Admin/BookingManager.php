<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Camper;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class BookingManager extends Component
{
    use WithPagination;

    public $camper_id;
    public $customer_first_name;
    public $customer_last_name;
    public $customer_email;
    public $customer_phone;
    public $date_range;
    public $start_date;
    public $end_date;
    public $total_price = 0;
    public $down_payment = 0;
    public $balance_payment = 0;

    public function updatedTotalPrice()
    {
        $this->calculatePayments();
    }

    private function calculatePayments()
    {
        $this->down_payment = round($this->total_price * 0.30, 2);
        $this->balance_payment = round($this->total_price - $this->down_payment, 2);
    }

    public function saveManualBooking()
    {
        $this->validate([
            'camper_id'     => 'required|exists:campers,id',
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'customer_email'     => 'required|string|lowercase|email|max:255',
            'customer_phone'     => 'nullable|min:8|max:20',
            'date_range'    => 'required',
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before:' . now()->addMonths(12)->addDay()->format('Y-m-d')
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                'before:' . now()->addMonths(13)->format('Y-m-d')
            ],
            'total_price'   => 'required|numeric|min:0',
        ]);

        $this->calculatePayments();

        $booking = new Booking();

        $booking->user_id = auth()->id();
        $booking->camper_id = $this->camper_id;
        $booking->customer_first_name = $this->customer_first_name;
        $booking->customer_last_name = $this->customer_last_name;
        $booking->customer_email = $this->customer_email;
        $booking->customer_phone = $this->customer_phone;
        $booking->start_date = $this->start_date;
        $booking->end_date = $this->end_date;
        $booking->total_price = $this->total_price;
        $booking->down_payment = $this->down_payment;
        $booking->down_paid = true;
        $booking->down_paid_at = now();
        $booking->balance_payment = $this->balance_payment;
        $booking->status = 'confirmed';
        $booking->payment_status = 'paid';
        $booking->documents_status = 'pending';
        $booking->terms_accepted = true;
        $booking->privacy_accepted = true;
        $booking->terms_and_privacy_accepted_at = now();
        $booking->contract_version = config('contracts.active_version');

        $booking->save();

        $this->reset(['camper_id', 'customer_first_name', 'customer_last_name', 'customer_email', 'customer_phone', 'start_date', 'end_date', 'total_price']);

        session()->flash('success', "Prenotazione #{$booking->id} creata.");

        return $this->redirect(route('dashboard'), navigate: true);
    }

    public function getBookedDatesProperty()
    {
        if (!$this->camper_id) {
            return [];
        }

        $limitDate = now()->addMonths(12);

        return Booking::where('camper_id', $this->camper_id)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where('start_date', '<=', $limitDate)
            ->get(['start_date', 'end_date'])
            ->flatMap(function ($booking) {
                $extendedStart = Carbon::parse($booking->start_date)->subDay();
                $extendedEnd = Carbon::parse($booking->end_date)->addDays(2);

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
            ->values()
            ->toArray();
    }

    public function updatedDateRange($value)
    {
        if (empty($value)) return;

        $separator = ' al ';

        if (str_contains($value, $separator)) {
            $dates = explode($separator, $value);
            $this->start_date = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->format('Y-m-d');
            $this->end_date = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->format('Y-m-d');
        } else {
            $date = Carbon::createFromFormat('d-m-Y', trim($value))->format('Y-m-d');
            $this->start_date = $date;
            $this->end_date = $date;
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.booking-manager', [
            'bookings' => Booking::with('camper')->latest()->paginate(10),
            'campers'  => Camper::all()
        ]);
    }
}
