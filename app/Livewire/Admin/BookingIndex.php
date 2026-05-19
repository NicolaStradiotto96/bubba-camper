<?php

namespace App\Livewire\Admin;

use App\Mail\BookingCancelled;
use App\Mail\BookingConfirmed;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BookingIndex extends Component
{
    use WithPagination;

    #[On('confirmBooking')]
    public function confirmBooking($bookingId)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'pending' && $booking->payment_status === 'paid') {
            $booking->status = 'confirmed';
            $booking->save();

            Mail::to($booking->customer_email)->send(new BookingConfirmed($booking));

            session()->flash('booked', "Prenotazione #{$booking->id} confermata!");
        }
    }

    public function cancelBooking($bookingId)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $booking = Booking::findOrFail($bookingId);

        $booking->status = 'cancelled';
        $booking->save();

        Mail::to($booking->customer_email)->send(new BookingCancelled($booking));

        session()->flash('cancelled', "Prenotazione #{$booking->id} annullata.");
    }

    #[On('markAsPaid')]
    public function markAsPaid($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        $booking->payment_status = 'fully_paid';
        $booking->save();

        session()->flash('booked', "Saldo registrato per #{$booking->id}. Ora la prenotazione è saldata al 100%.");
    }

    public function getStatsProperty()
    {
        return [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'earnings' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.booking-index', [
            'bookings' => Booking::with('camper')
                ->latest()
                ->paginate(10)
        ]);
    }
}
