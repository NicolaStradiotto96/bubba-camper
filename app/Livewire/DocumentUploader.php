<?php

namespace App\Livewire;

use App\Mail\DocumentRecieved;
use App\Mail\DocumentRecievedNotification;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentUploader extends Component
{
    use WithFileUploads;

    public $bookingId;
    public $existingFiles = [];

    public $driver_license_front;
    public $driver_license_back;
    public $id_card_front;
    public $id_card_back;

    public function mount($bookingId = null, $existingFiles = [])
    {
        $this->bookingId = $bookingId;
        $this->existingFiles = $existingFiles;
    }

    protected $listeners = [
        'setBookingId' => 'setBooking'
    ];

    public function setBooking($id)
    {
        $query = Booking::query();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $booking = $query->find($id);

        if (!$booking) {
            $this->dispatch('close-doc-modal');
            $this->dispatch('swal-error');
            return;
        }

        $this->existingFiles = [
            'driver_license_front' => !empty($booking->driver_license_front_path),
            'driver_license_back'  => !empty($booking->driver_license_back_path),
            'id_card_front'        => !empty($booking->id_card_front_path),
            'id_card_back'         => !empty($booking->id_card_back_path),
        ];

        $this->bookingId = $id;
        $this->resetForm();

        $this->dispatch('$refresh');
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

        $query = Booking::query();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $booking = $query->findOrFail($this->bookingId);

        $rules = [];
        $newPaths = [];
        $fields = [
            'driver_license_front' => 'driver_license_front_path',
            'driver_license_back'  => 'driver_license_back_path',
            'id_card_front'        => 'id_card_front_path',
            'id_card_back'         => 'id_card_back_path',
        ];

        foreach ($fields as $field => $dbColumn) {
            if (!empty($this->existingFiles[$field])) {
                $this->$field = null;
                continue;
            }

            $rules[$field] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
        }

        $this->validate($rules);

        DB::transaction(function () use ($booking, $fields, &$newPaths) {
            $folder = 'documents/' . $this->bookingId;

            foreach ($fields as $field => $dbColumn) {
                if ($this->$field) {
                    $newPaths[$dbColumn] = $this->$field->store($folder, 'local');
                }
            }

            if (!empty($newPaths)) {
                $newPaths['documents_status'] = 'uploaded';
                $booking->update($newPaths);

                try {
                    Mail::to($booking->customer_email)->send(new DocumentRecieved($booking));
                    Mail::to(config('app.admin_email'))->send(new DocumentRecievedNotification($booking));
                } catch (\Exception $e) {
                    Log::error("Errore invio notifica documenti: " . $e->getMessage());
                }
            }
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
