<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentUploader extends Component
{
    use WithFileUploads;

    public $bookingId;

    public $driver_license_front;
    public $driver_license_back;
    public $id_card_front;
    public $id_card_back;

    public function mount($bookingId = null)
    {
        $this->bookingId = $bookingId;
    }

    protected $listeners = [
        'setBookingId' => 'setBooking'
    ];

    public function setBooking($id)
    {
        $this->bookingId = $id;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['driver_license_front', 'driver_license_back', 'id_card_front', 'id_card_back']);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->dispatch('$refresh');
    }

    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);
    }

    public function uploadDocuments()
    {
        if (!$this->bookingId) return;

        $this->validate([
            'driver_license_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'driver_license_back'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_card_front'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_card_back'         => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        \DB::transaction(function () {
            $booking = Booking::findOrFail($this->bookingId);

            $folder = 'documents/' . $this->bookingId;
            $paths = [
                'driver_license_front_path' => $this->driver_license_front->store($folder, 'local'),
                'driver_license_back_path'  => $this->driver_license_back->store($folder, 'local'),
                'id_card_front_path'        => $this->id_card_front->store($folder, 'local'),
                'id_card_back_path'         => $this->id_card_back->store($folder, 'local'),
                'documents_status'          => 'uploaded'
            ];

            $booking->update($paths);
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
