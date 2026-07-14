<?php

namespace App\Livewire;

use App\Mail\PenaltyDamage;
use App\Models\Booking;
use App\Models\Damage;
use App\Models\DamagePhoto;
use App\Models\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
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

    // IS ADMIN?
    public function mount(Booking $booking, $damage_id = null)
    {
        if (!auth()->user()?->is_admin) {
        abort(403);
    }

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

    // RULES
    protected function rules()
    {
        return [
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'photos.*' => 'file|mimes:pdf,png,jpg,jpeg|max:5120',
        ];
    }

    // ERROR MESSAGES
    protected function messages()
    {
        return [
            'amount.required'      => 'Inserisci l\'importo della penale.',
            'amount.numeric'       => 'L\'importo deve essere un numero.',
            'amount.min'           => 'L\'importo non può essere negativo.',
            'description.required' => 'La descrizione del danno è obbligatoria.',
            'photos.*.mimes'       => 'Solo i file di tipo pdf, jpeg, png o jpg sono permessi.',
            'photos.*.max'         => 'Ogni foto deve pesare al massimo 5MB.',
        ];
    }

    // SHOW PHOTO
    public function updatedTemporaryPhotos($value)
    {
        foreach ($value as $file) {
            $this->photos[] = $file;
        }

        $this->temporary_photos = [];
    }

    // REMOVE PHOTO
    public function removePhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    // REMOVE EXISTING PHOTO
    #[On('removeExistingPhoto')]
    public function removeExistingPhoto($id)
    {
        $photo = DamagePhoto::findOrFail($id);
        $damage = $photo->damage;

        Storage::disk('public')->delete($photo->path);

        $photo->delete();

        $this->logDamage('damage_updated', "Eliminata foto dal danno #{$damage->id} per il camper #{$this->booking->camper_id}", $damage);

        $this->existing_photos = $this->existing_photos->reject(fn($item) => $item->id == $id);
    }

    // SAVE DAMAGE
    public function saveDamage()
    {

        $this->validate($this->rules(), $this->messages());

        $isUpdate = !empty($this->damageId);

        $amountChanged = $isUpdate && ((float)$this->amount != (float)$this->originalAmount);

        $damage = Damage::updateOrCreate(
            ['id' => $this->damageId],
            [
                'booking_id' => $this->booking->id,
                'amount' => $this->amount,
                'description' => $this->description,
                'status'      => 'pending',
                'camper_id'   => $this->booking->camper_id,
            ]
        );

        foreach ($this->photos as $photo) {
            $path = $photo->store('damages', 'public');
            $damage->photos()->create(['path' => $path]);
        }

        $this->photos = [];

        try {
            if (!$isUpdate) {
                Mail::to($this->booking->customer_email)->send(new PenaltyDamage($damage, false));
                $this->logDamage('damage_reported', "Segnalato danno da {$this->amount}€ per il camper #{$this->booking->camper_id}", $damage);
            } elseif ($amountChanged) {
                Mail::to($this->booking->customer_email)->send(new PenaltyDamage($damage, true));
                $this->logDamage('damage_updated', "Aggiornato danno da {$this->originalAmount}€ a {$this->amount}€ per il camper #{$this->booking->camper_id}", $damage);
            }
        } catch (\Exception $e) {
            \Log::error("Errore invio mail danno (ID: {$damage->id}, Camper: {$this->booking->camper_id}): " . $e->getMessage());
        }

        session()->flash('swal-success', $isUpdate ? 'Danno aggiornato con successo!' : 'Danno segnalato con successo!');
        return redirect()->route('damage.index');
    }

    // UPDATE ERRORS
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules(), $this->messages());
    }

    // RENDER
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.damage-manager');
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
