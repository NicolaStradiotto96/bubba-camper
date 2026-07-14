<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Damage;
use App\Models\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DamageIndex extends Component
{
    use WithPagination;

    public $search_id = '';
    public $selectedBooking;
    public $selectedDamage = null;

    // IS ADMIN?
    public function mount()
    {
        if (!auth()->user()?->is_admin) {
        abort(403);
    }
    }

    // OPEN MODAL
    public function showDamage($id)
    {
        $this->selectedDamage = Damage::with(['photos', 'booking'])->findOrFail($id);

        $this->dispatch('open-modal');
        $this->dispatch('contentChanged');
    }

    // REMOVE DAMAGE
    #[On('removeDamage')]
    public function removeDamage($id)
    {
        $damage = Damage::findOrFail($id);

        $this->logDamage('damage_deleted', "Danno #{$damage->id} eliminato per il camper #{$damage->camper_id}", $damage);

        foreach ($damage->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $damage->photos()->delete();

        $damage->delete();

        $this->dispatch('swal-success', ['message' => 'Danno eliminato con successo!']);
    }

    // RESET PAGE
    public function updatingSearchId()
    {
        $this->resetPage();
    }

    // RENDER
    #[Layout('layouts.app')]
    public function render()
    {
        $query = Damage::query()->with(['booking', 'photos'])->latest();

        $cleanSearch = trim(str_replace('#', '', $this->search_id));

        if (!empty($cleanSearch)) {
            $query->where(function ($q) use ($cleanSearch) {
                $q->where('id', $cleanSearch);
                if (is_numeric($cleanSearch)) {
                    $q->orWhere('booking_id', $cleanSearch);
                }
            });
        }

        return view('livewire.damage-index', [
            'damages' => $query->paginate(7),
            'selectedDamage' => $this->selectedDamage,
        ]);
    }

    // LOG
    private function logDamage(string $type, string $message, Damage $damage)
    {
        Log::create([
            'booking_id' => $damage->booking_id,
            'type'       => $type,
            'message'    => $message,
            'context'    => [
                'user_id'    => auth()->id(),
                'ip_address' => request()->ip(),
                'damage_id'  => $damage->id,
                'camper_id'  => $this->booking->camper_id,
            ],
        ]);
    }
}
