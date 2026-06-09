<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentUploader extends Component
{
    use WithFileUploads;

    #[Locked]
    public $bookingId;
    public $driver_license;
    public $id_card;

    public function mount($bookingId = null)
    {
        $this->bookingId = $bookingId;
    }

    protected $listeners = ['setBookingId' => 'setBooking'];

    public function setBooking($id)
    {
        $this->bookingId = $id;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['driver_license', 'id_card']);

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function uploadDocuments()
    {
        if (!$this->bookingId) return;

        $this->validate([
            'driver_license' => 'required|file|mimes:jpeg,png,jpg,pdf|max:8192',
            'id_card' => 'required|file|mimes:jpeg,png,jpg,pdf|max:8192',
        ]);

        \DB::transaction(function () {
            $booking = Booking::findOrFail($this->bookingId);

            $licensePath = $this->driver_license->store('documents/' . $this->bookingId, 'local');
            $idCardPath = $this->id_card->store('documents/' . $this->bookingId, 'local');

            $booking->update([
                'driver_license_path' => $licensePath,
                'id_card_path' => $idCardPath,
                'documents_status' => 'uploaded'
            ]);
        });

        $this->resetForm();

        $this->dispatch('notify', message: 'Documenti inviati correttamente!');

        $this->dispatch('close-doc-modal');

        $this->dispatch('refresh-page');
    }

    public function render()
    {
        return view('livewire.document-uploader');
    }
}
