<?php

namespace App\Livewire;

use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Models\Agency;
use App\Models\Rfq;
use App\Models\RfqItem;
use App\Models\ActivityLog;
use Livewire\Component;
use Illuminate\Support\Collection;

class ProcurementForm extends Component
{
    public string $procurement_number = '';
    public string $date_prepared = '';
    public string $delivery_deadline = '';
    public string $status = 'Draft';
    public string $notes = '';

    public array $items = [
        [
            'agency_id' => '',
            'rfq_item_id' => '',
            'brand' => '',
            'item_description' => '',
            'unit' => '',
            'quantity' => '',
            'unit_price' => '',
            'status' => 'Pending',
            'notes' => '',
        ],
    ];

    public string $rfqSearch = '';
    public ?int $selectedRfqId = null;
    public bool $showRfqPicker = false;
    public ?int $procurementId = null;

    public function mount(?int $procurementId = null): void
    {
        if ($procurementId) {
            $this->procurementId = $procurementId;
            $procurement = Procurement::with('items.agency')->findOrFail($procurementId);

            $this->procurement_number = $procurement->procurement_number;
            $this->date_prepared = $procurement->date_prepared->format('Y-m-d');
            $this->delivery_deadline = $procurement->delivery_deadline ? $procurement->delivery_deadline->format('Y-m-d') : '';
            $this->status = $procurement->status;
            $this->notes = $procurement->notes ?? '';

            $this->items = array_values($procurement->items->map(fn($i) => [
                'agency_id' => (string) ($i->agency_id ?? ''),
                'rfq_item_id' => (string) ($i->rfq_item_id ?? ''),
                'brand' => $i->brand ?? '',
                'item_description' => $i->item_description,
                'unit' => $i->unit,
                'quantity' => (string) $i->quantity,
                'unit_price' => (string) ($i->unit_price ?? ''),
                'status' => $i->status,
                'notes' => $i->notes ?? '',
            ])->toArray());
        } else {
            $this->date_prepared = now()->format('Y-m-d');
            $this->procurement_number = Procurement::generateNumber();
        }
    }

    public function toggleRfqPicker(): void
    {
        $this->showRfqPicker = !$this->showRfqPicker;
        if (!$this->showRfqPicker) {
            $this->selectedRfqId = null;
            $this->rfqSearch = '';
        }
    }

    public function selectRfq(int $rfqId): void
    {
        $this->selectedRfqId = $rfqId;
        $this->showRfqPicker = false;
        $this->rfqSearch = '';
    }

    public function addRfqItems(): void
    {
        if (!$this->selectedRfqId) {
            return;
        }

        $rfq = Rfq::with('items')->findOrFail($this->selectedRfqId);

        foreach ($rfq->items as $rfqItem) {
            $existing = collect($this->items)->first(fn($item) =>
                $item['rfq_item_id'] == $rfqItem->id && $item['agency_id'] == $rfq->agency_id
            );

            if (!$existing) {
                $this->items[] = [
                    'agency_id' => (string) $rfq->agency_id,
                    'rfq_item_id' => (string) $rfqItem->id,
                    'brand' => $rfqItem->brand ?? '',
                    'item_description' => $rfqItem->item_description,
                    'unit' => $rfqItem->unit,
                    'quantity' => (string) $rfqItem->quantity,
                    'unit_price' => (string) ($rfqItem->unit_price ?? ''),
                    'status' => 'Pending',
                    'notes' => '',
                ];
            }
        }

        $this->selectedRfqId = null;
        $this->dispatch('item-added');
    }

    public function addItem(): void
    {
        $hasEmpty = collect($this->items)->some(
            fn($item) => trim($item['item_description'] ?? '') === '' ||
                         trim($item['unit'] ?? '') === '' ||
                         trim($item['quantity'] ?? '') === ''
        );

        if ($hasEmpty) {
            session()->flash('error', 'Please fill in all existing item fields before adding a new one.');
            return;
        }

        $this->items[] = [
            'agency_id' => '',
            'rfq_item_id' => '',
            'brand' => '',
            'item_description' => '',
            'unit' => '',
            'quantity' => '',
            'unit_price' => '',
            'status' => 'Pending',
            'notes' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        array_splice($this->items, $index, 1);

        if (empty($this->items)) {
            $this->items = [
                [
                    'agency_id' => '',
                    'rfq_item_id' => '',
                    'brand' => '',
                    'item_description' => '',
                    'unit' => '',
                    'quantity' => '',
                    'unit_price' => '',
                    'status' => 'Pending',
                    'notes' => '',
                ],
            ];
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'date_prepared' => 'required|date',
            'delivery_deadline' => 'nullable|date|after_or_equal:date_prepared',
            'status' => 'required|in:Draft,Submitted,Approved,Ordered,Delivered,Cancelled',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['delivery_deadline'])) {
            $validated['delivery_deadline'] = null;
        }

        $validated['total_amount'] = collect($this->items)->sum(fn($item) => (float) $item['unit_price'] * (float) $item['quantity']);

        if ($this->procurementId) {
            $procurement = Procurement::findOrFail($this->procurementId);
            $procurement->update($validated);
            $oldStatus = $procurement->getOriginal('status');
            $oldStatus !== $procurement->status
                ? ActivityLog::log('procurement.status_changed', $procurement, ['status' => $oldStatus], ['status' => $procurement->status])
                : ActivityLog::log('procurement.updated', $procurement);
        } else {
            $validated['procurement_number'] = Procurement::generateNumber();
            $validated['prepared_by'] = auth()->id();
            $procurement = Procurement::create($validated);
            ActivityLog::log('procurement.created', $procurement);
        }

        $procurement->items()->delete();

        foreach ($this->items as $itemData) {
            if (trim($itemData['item_description'] ?? '') === '' || trim($itemData['unit'] ?? '') === '' || trim($itemData['quantity'] ?? '') === '') {
                continue;
            }

            $procurement->items()->create([
                'agency_id' => $itemData['agency_id'] ?: null,
                'rfq_item_id' => $itemData['rfq_item_id'] ?: null,
                'brand' => $itemData['brand'] ?? '',
                'item_description' => $itemData['item_description'],
                'unit' => $itemData['unit'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'] ?: null,
                'total_price' => ($itemData['unit_price'] ?: 0) * ($itemData['quantity'] ?: 0),
                'status' => $itemData['status'],
                'notes' => $itemData['notes'] ?? '',
            ]);
        }

        session()->flash('message', $this->procurementId
            ? "Procurement {$procurement->procurement_number} updated successfully."
            : "Procurement {$procurement->procurement_number} created successfully."
        );

        $this->redirect(route('procurements.show', $procurement));
    }

    public function getAwardedRfqsProperty()
    {
        return Rfq::whereIn('status', ['Awarded', 'Quoted'])
            ->when($this->rfqSearch, fn($q, $search) => $q->where('rfq_number', 'like', "%{$search}%")
                ->orWhereHas('agency', fn($q) => $q->where('name', 'like', "%{$search}%")))
            ->orderBy('rfq_number')
            ->get();
    }

    public function render()
    {
        return view('livewire.procurement-form', [
            'agencies' => Agency::orderBy('name')->get(),
            'awardedRfqs' => $this->awardedRfqs,
        ]);
    }
}