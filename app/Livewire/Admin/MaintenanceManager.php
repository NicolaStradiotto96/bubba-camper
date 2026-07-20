<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Camper;
use App\Models\Log;
use App\Models\Maintenance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class MaintenanceManager extends Component
{
    use WithPagination;

    public $camper_id;
    public $start_date;
    public $end_date;
    public $reason;
    public $editingId = null;
    public $isFetchingDates = false;

    // IS ADMIN?
    public function mount()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Accesso non autorizzato.');
        }
    }

    // CANCEL EDIT
    public function cancelEdit()
    {
        $this->resetValidation();
        $this->isFetchingDates = false;
        $this->reset(['editingId', 'camper_id', 'start_date', 'end_date', 'reason']);
        $this->dispatch('set-flatpickr-date', start: null, end: null);
    }

    // SAVE MAINTENANCE
    public function saveBlock()
    {

        $this->validate([
            'camper_id'  => 'required|exists:campers,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string'
        ], [
            'camper_id.required'  => 'Devi selezionare un camper',
            'camper_id.exists'    => 'Il camper selezionato non è valido',
            'start_date.required' => 'Devi selezionare una data',
            'end_date.required'   => 'Devi selezionare una data',
            'end_date.after_or_equal' => 'La data di fine non può essere precedente a quella di inizio',
        ]);

        try {
            $block = Maintenance::updateOrCreate(
                ['id' => $this->editingId],
                [
                    'camper_id'  => $this->camper_id,
                    'start_date' => Carbon::createFromFormat('d-m-Y', $this->start_date)->format('Y-m-d'),
                    'end_date'   => Carbon::createFromFormat('d-m-Y', $this->end_date)->format('Y-m-d'),
                    'reason'     => $this->reason
                ]
            );
        } catch (\Exception $e) {
            $this->addError('start_date', 'Errore nel formato data.');
            $this->addError('end_date', 'Errore nel formato data.');
            return;
        }

        $action = $this->editingId ? 'aggiornata' : 'creata';
        $this->logMaintenance('maintenance_' . ($this->editingId ? 'updated' : 'created'), "Indisponibilità $action per il camper #{$this->camper_id}", $block);

        $this->cancelEdit();

        $this->dispatch('swal-success', 'Indisponibilità aggiunta con successo!');
    }

    // EDIT MAINTENANCE
    public function editBlock($id)
    {
        $this->resetValidation();

        $block = Maintenance::findOrFail($id);

        $this->editingId = $block->id;
        $this->camper_id = $block->camper_id;
        $this->reason = $block->reason;
        $this->start_date = $block->start_date->format('d-m-Y');
        $this->end_date = $block->end_date->format('d-m-Y');

        $this->dispatch('set-flatpickr-date', start: $this->start_date, end: $this->end_date);
    }

    // REMOVE MAINTENANCE
    #[On('removeBlock')]
    public function removeBlock($id)
    {
        $block = Maintenance::findOrFail($id);

        if ($block->end_date->isPast()) {
            $this->dispatch('swal-error', 'Non puoi eliminare una indisponibilità già conclusa.');
            return;
        }

        $this->logMaintenance('maintenance_deleted', "Indisponibilità eliminata per il camper #{$block->camper_id}", $block);

        $block->delete();

        $this->dispatch('swal-success', 'Indisponibilità eliminata con successo!');
    }

    // CALENDAR
    public function getBookedDatesProperty()
    {
        if (!$this->camper_id) {
            return [];
        }

        $limitDate = now()->addMonths(12);

        $bookings = Booking::query()
            ->where('camper_id', $this->camper_id)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where('start_date', '<=', $limitDate)
            ->where(function ($query) {
                $query->where('payment_status', 'paid')
                    ->orWhere('created_at', '>=', now()->subMinutes(15));
            })
            ->get(['start_date', 'end_date']);

        $maintenances = Maintenance::where('camper_id', $this->camper_id)
            ->where('start_date', '<=', $limitDate)
            ->when($this->editingId, function ($query) {
                $query->where('id', '!=', $this->editingId);
            })
            ->get(['start_date', 'end_date']);

        return $bookings->concat($maintenances)
            ->flatMap(function ($item) {
                $isBooking = $item instanceof Booking;

                $start = Carbon::parse($item->start_date);
                $end = Carbon::parse($item->end_date);

                $extendedStart = $isBooking ? $start->copy()->subDay() : $start->copy();
                $extendedEnd = $isBooking ? $end->copy()->addDays(2) : $end->copy()->addDay();

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
            ->unique()
            ->values()
            ->toArray();
    }

    public function validateRange()
    {
        if (empty($this->start_date) || empty($this->end_date)) return;

        try {
            $start = Carbon::createFromFormat('d-m-Y', $this->start_date)->format('Y-m-d');
            $end = Carbon::createFromFormat('d-m-Y', $this->end_date)->format('Y-m-d');
        } catch (\Exception $e) {
            $this->addError('start_date', 'Errore nel formato data.');
            $this->addError('end_date', 'Errore nel formato data.');
            return;
        }

        $isBooked = Booking::where('camper_id', $this->camper_id)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($query) use ($start, $end) {
                        $query->where('start_date', '<', $start)
                            ->where('end_date', '>', $end);
                    });
            })->exists();

        $isMaintained = Maintenance::where('camper_id', $this->camper_id)
            ->where('id', '!=', $this->editingId)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($query) use ($start, $end) {
                        $query->where('start_date', '<', $start)
                            ->where('end_date', '>', $end);
                    });
            })->exists();

        if ($isBooked || $isMaintained) {
            $this->addError('maintenance_range', 'Il periodo selezionato include date già occupate.');
            $this->dispatch('clear-calendar');
        }
    }

    // IS EDITING
    public function getIsDirtyProperty()
    {
        return empty($this->editingId) && (
            !empty($this->camper_id) ||
            !empty($this->start_date) ||
            !empty($this->end_date) ||
            !empty($this->reason)
        );
    }

    // RESET ERRORS
    public function updatedCamperId()
    {
        $this->isFetchingDates = true;
        $this->resetValidation();
        $this->dispatch('loading-dates');
    }

    public function finishLoading()
    {
        $this->isFetchingDates = false;
        $this->dispatch('dates-loaded');
    }

    public function updatedStartDate()
    {
        $this->resetValidation();
    }

    public function updatedEndDate()
    {
        $this->resetValidation();
    }

    // RENDER
    #[Layout('layouts.app')]
    #[Title('Gestione Indisponibilità')]
    public function render()
    {
        $query = Maintenance::with('camper')->latest();

        if ($this->camper_id) {
            $query->where('camper_id', $this->camper_id);
        }

        $blocks = $query->paginate(3);

        return view('livewire.admin.maintenance-manager', [
            'campers' => Camper::all(),
            'blocks'  => $blocks
        ]);
    }

    // LOG
    private function logMaintenance(string $type, string $message, Maintenance $maintenance)
    {
        Log::create([
            'type'    => $type,
            'message' => $message,
            'context' => [
                'user_id'        => auth()->id(),
                'ip_address'     => request()->ip(),
                'maintenance_id' => $maintenance->id,
                'camper_id'      => $maintenance->camper_id,
                'period'         => $maintenance->start_date->format('d/m/Y') . ' - ' . $maintenance->end_date->format('d/m/Y'),
                'reason'         => $maintenance->reason,
            ],
        ]);
    }
}
