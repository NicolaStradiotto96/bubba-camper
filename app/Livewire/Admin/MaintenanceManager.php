<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Camper;
use App\Models\Maintenance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
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

    public function saveBlock()
    {
        $this->validate([
            'camper_id'  => 'required|exists:campers,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string'
        ]);

        Maintenance::updateOrCreate(
            ['id' => $this->editingId],
            [
                'camper_id'  => $this->camper_id,
                'start_date' => Carbon::createFromFormat('d-m-Y', $this->start_date)->format('Y-m-d'),
                'end_date'   => Carbon::createFromFormat('d-m-Y', $this->end_date)->format('Y-m-d'),
                'reason'     => $this->reason
            ]
        );

        $this->reset(['editingId', 'camper_id', 'start_date', 'end_date', 'reason']);
        $this->dispatch('set-flatpickr-date', start: null, end: null);
        session()->flash('success', 'Operazione completata con successo.');
    }

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

    public function cancelEdit()
    {
        $this->reset(['editingId', 'camper_id', 'start_date', 'end_date', 'reason']);
        $this->dispatch('set-flatpickr-date', start: null, end: null);
    }

    public function removeBlock($id)
    {
        Maintenance::find($id)->delete();
    }

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
        if (!$this->start_date || !$this->end_date) return;

        $start = Carbon::createFromFormat('d-m-Y', $this->start_date)->format('Y-m-d');
        $end = Carbon::createFromFormat('d-m-Y', $this->end_date)->format('Y-m-d');

        $isBooked = Booking::where('camper_id', $this->camper_id)
            ->whereNotIn('status', Booking::getExcludedStatuses())
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
            })->exists();

        $isMaintained = Maintenance::where('camper_id', $this->camper_id)
            ->where('id', '!=', $this->editingId)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
            })->exists();

        if ($isBooked || $isMaintained) {
            $this->addError('maintenance_range', 'Il periodo selezionato include date già occupate.');
            $this->dispatch('clear-calendar');
        }
    }

    public function getIsDirtyProperty()
    {
        return empty($this->editingId) && (
            !empty($this->camper_id) ||
            !empty($this->start_date) ||
            !empty($this->end_date) ||
            !empty($this->reason)
        );
    }

    #[Layout('layouts.app')]
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
}
