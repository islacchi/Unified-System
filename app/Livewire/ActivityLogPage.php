<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogPage extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $actionFilter = '';
    public int    $perPage      = 25;

    protected $queryString = [
        'search'       => ['except' => ''],
        'actionFilter' => ['except' => ''],
    ];

    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedActionFilter(): void { $this->resetPage(); }

    public function getLogsProperty()
    {
        return ActivityLog::with('user')
            ->when($this->search, fn($q) =>
                $q->where('description', 'like', "%{$this->search}%")
            )
            ->when($this->actionFilter, fn($q) =>
                $q->where('action', $this->actionFilter)
            )
            ->latest()
            ->paginate($this->perPage);
    }

    public function getAvailableActionsProperty(): array
    {
        return ActivityLog::distinct()
            ->pluck('action')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.activity-log-page', [
            'logs'             => $this->logs,
            'availableActions' => $this->availableActions,
        ]);
    }
}