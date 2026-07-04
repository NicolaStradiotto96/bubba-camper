<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Damage;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class DamageIndex extends Component
{
    use WithPagination;

    public $search_id = '';
    public $selectedBooking;
    public $selectedDamage = null;

    public function updatingSearchId()
    {
        $this->resetPage();
    }

    public function openBookingDetails($bookingId)
    {
        $this->selectedBooking = Booking::find($bookingId);
        $this->dispatch('open-booking-modal');
    }

    public function showDamage($id)
    {
        $this->selectedDamage = Damage::find($id);
        $this->dispatch('open-modal');
        $this->dispatch('contentChanged');
    }

    public function removeDamage($id)
    {
        if (!auth()->user()?->is_admin) {
            abort(403);
        }

        $damage = Damage::findOrFail($id);

        foreach ($damage->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $damage->photos()->delete();

        $damage->delete();

        $this->dispatch('notify', message: 'Danno eliminato con successo!');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = Damage::query()->with(['booking', 'photos'])->latest();

        $cleanSearch = trim(str_replace('#', '', $this->search_id));

        if (!empty($cleanSearch)) {
            $query->where('booking_id', 'like', $cleanSearch . '%');
        }

        return view('livewire.damage-index', [
            'damages' => $query->paginate(7),
            'selectedDamage' => $this->selectedDamage,
        ]);
    }
}
