<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Camper;
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
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
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
        return \App\Models\Booking::where('status', '!=', 'cancelled')
            ->get(['start_date', 'end_date'])
            ->flatMap(function ($booking) {
                $dates = [];
                $current = \Carbon\Carbon::parse($booking->start_date);
                $end = \Carbon\Carbon::parse($booking->end_date);

                while ($current <= $end) {
                    $dates[] = $current->format('d-m-Y');
                    $current->addDay();
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
            $this->start_date = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dates[0]))->format('Y-m-d');
            $this->end_date = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dates[1]))->format('Y-m-d');
        } else {
            $date = \Carbon\Carbon::createFromFormat('d-m-Y', trim($value))->format('Y-m-d');
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
