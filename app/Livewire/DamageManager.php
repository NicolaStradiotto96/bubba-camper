<?php

namespace App\Livewire;

use App\Mail\PenaltyDamage;
use App\Models\Booking;
use App\Models\Damage;
use App\Models\DamagePhoto;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class DamageManager extends Component
{
    use WithFileUploads;

    public $booking;
    public $damageId;
    public $amount;
    public $description;
    public $photos = [];
    public $temporary_photos = [];
    public $existing_photos = [];
    public $originalAmount;

    public function mount(Booking $booking, $damage_id = null)
    {
        $this->booking = $booking;
        $this->damageId = $damage_id;

        if ($damage_id) {
            $damage = Damage::findOrFail($damage_id);
            $this->amount = $damage->amount;
            $this->description = $damage->description;
            $this->existing_photos = $damage->photos;
            $this->originalAmount = $damage->amount;
        }
    }

    public function updatedTemporaryPhotos($value)
    {
        foreach ($value as $file) {
            $this->photos[] = $file;
        }

        $this->temporary_photos = [];
    }

    public function removePhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    public function removeExistingPhoto($photoId)
    {
        $photo = DamagePhoto::findOrFail($photoId);

        Storage::disk('public')->delete($photo->path);

        $photo->delete();

        $this->existing_photos = $this->existing_photos->reject(function ($item) use ($photoId) {
            return $item->id == $photoId;
        });
    }

    public function saveDamage()
    {
        if (!auth()->user()?->is_admin) abort(403);

        $this->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'photos.*' => 'image|max:10240',
        ]);

        $isUpdate = !empty($this->damageId);

        $amountChanged = $isUpdate && ((float)$this->amount != (float)$this->originalAmount);

        $damage = Damage::updateOrCreate(
            ['id' => $this->damageId],
            [
                'booking_id' => $this->booking->id,
                'amount' => $this->amount,
                'description' => $this->description,
            ]
        );

        foreach ($this->photos as $photo) {
            $path = $photo->store('damages', 'public');
            $damage->photos()->create(['path' => $path]);
        }

        if (!$isUpdate) {
            Mail::to($this->booking->customer_email)->send(new PenaltyDamage($damage, false));
            $this->booking->logs()->create([
                'type' => 'damage_reported',
                'message' => "Segnalato danno da {$this->amount}€",
            ]);
        } elseif ($amountChanged) {
            Mail::to($this->booking->customer_email)->send(new PenaltyDamage($damage, true));
            $this->booking->logs()->create([
                'type' => 'damage_updated',
                'message' => "Aggiornato danno da {$this->originalAmount}€ a {$this->amount}€",
            ]);
        }

        $damage->status = 'pending';
        $damage->save();

        $this->dispatch('notify', message: $isUpdate ? 'Danno aggiornato!' : 'Danno segnalato!');
        return redirect()->route('damage.index');
    }

    public static function getAmountForBooking($bookingId)
    {
        return Damage::where('booking_id', $bookingId)
            ->where('status', 'pending')
            ->sum('amount');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.damage-manager');
    }
}
