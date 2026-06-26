<?php

namespace App\Livewire;

use App\Models\Procurement;
use Livewire\Component;
use Livewire\WithPagination;

class ProcurementTracker extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $itemSearch = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'itemSearch' => ['except' => ''],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }
    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo(): void { $this->resetPage(); }
    public function updatingItemSearch(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $procurement = Procurement::findOrFail($id);

        if ($procurement->status !== 'Draft') {
            session()->flash('error', 'Only drafts can be deleted.');
            return;
        }

        $procurement->delete();
        session()->flash('message', "Procurement deleted successfully.");
    }

    public function render()
    {
        $query = Procurement::with(['preparedBy', 'items.agency']);

        if ($this->search) {
            $query->where('procurement_number', 'like', "%{$this->search}%");
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFrom) {
            $query->whereDate('date_prepared', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('date_prepared', '<=', $this->dateTo);
        }

        if ($this->itemSearch) {
            $query->whereHas('items', function($q) {
                $q->where('item_description', 'like', "%{$this->itemSearch}%")
                  ->orWhere('brand', 'like', "%{$this->itemSearch}%");
            });
        }

        $procurements = $query->orderByDesc('date_prepared')->paginate(15);

        return view('livewire.procurement-tracker', [
            'procurements' => $procurements,
            'statuses' => ['Draft', 'Submitted', 'Approved', 'Ordered', 'Delivered', 'Cancelled'],
        ]);
    }
}