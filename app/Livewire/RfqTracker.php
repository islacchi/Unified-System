<?php

namespace App\Livewire;

use App\Models\Rfq;
use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class RfqTracker extends Component
{
    use WithPagination;

    // -------------------------------------------------------------------------
    // Filter, sort, and UI state
    // -------------------------------------------------------------------------

    // Search term for filtering by RFQ number or agency name
    public string $search  = '';

    // Active status filter tab — 'all' shows everything
    public string $status  = 'all';

    // Column currently being sorted and its direction
    public string $sortBy  = 'deadline';
    public string $sortDir = 'asc';

    // Tracks which RFQ rows have their document checklist dropdown open
    public array $openRows = [];

    // Persist search and status filters in the URL query string
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
    ];

    // Reset to page 1 whenever search or status filter changes
    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }

    // -------------------------------------------------------------------------
    // Filter by status tab
    // -------------------------------------------------------------------------
    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->resetPage();
    }

    // -------------------------------------------------------------------------
    // Column sorting
    // Toggles direction if the same column is clicked again,
    // otherwise switches to the new column with ascending order
    // -------------------------------------------------------------------------
    public function sortColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $column;
            $this->sortDir = 'asc';
        }
    }

    // -------------------------------------------------------------------------
    // Toggle the document checklist dropdown for a specific RFQ row
    // Adds to openRows to open, removes to close
    // -------------------------------------------------------------------------
    public function toggleOpen(int $rfqId): void
    {
        if (in_array($rfqId, $this->openRows)) {
            $this->openRows = array_values(
                array_filter($this->openRows, fn($id) => $id !== $rfqId)
            );
        } else {
            $this->openRows[] = $rfqId;
        }
    }

    // -------------------------------------------------------------------------
    // Manually update the status of an RFQ from the tracker
    // -------------------------------------------------------------------------
    public function updateStatus(int $rfqId, string $status): void
    {
        $rfq = Rfq::findOrFail($rfqId);
        $old_status = $rfq->status;
        $rfq->update(['status' => $status]);
        ActivityLog::log('rfq.status_changed', $rfq, ['status' => $old_status], ['status' => $status]);
        session()->flash('message', "RFQ #{$rfq->rfq_number} updated to {$status}.");
    }

    // -------------------------------------------------------------------------
    // Toggle a document checkbox on/off for a given RFQ
    // When Notice of Award (NOA) is checked, status auto-updates to Awarded.
    // When NOA is unchecked, status reverts to Quoted.
    // -------------------------------------------------------------------------
public function toggleDoc(int $rfqId, string $doc): void
{
    $rfq     = Rfq::with('items')->findOrFail($rfqId);
    $docs    = $rfq->documents ?? [];
    $current = $docs[$doc] ?? false;

    // Prevent checking NOA, PO, or NTP if the RFQ is Lost
    if (in_array($doc, ['notice_of_award', 'purchase_order', 'ntp']) && $rfq->status === 'Lost') {
        $this->addError('doc_error_' . $rfqId, 'Cannot mark this document on a Lost RFQ.');
        return;
    }

    // Prevent checking NOA, PO, or NTP unless ALL items have a unit price
    if (in_array($doc, ['notice_of_award', 'purchase_order', 'ntp']) && !$current) {
        $allPriced = $rfq->items->every(fn($item) => !empty($item->unit_price));
        if (!$allPriced) {
            $this->addError('doc_error_' . $rfqId, 'Cannot mark award documents — all items must have a quoted price first.');
            return;
        }
    }

    // Toggle the document
    $docs[$doc] = $current ? false : ['received' => true, 'date' => null];
    $rfq->update(['documents' => $docs]);

    ActivityLog::log('rfq.document_toggled', $rfq, null, ['doc' => $doc, 'checked' => !$current]);

    // Re-evaluate status based on current state of all 3 award docs
    if (in_array($doc, ['notice_of_award', 'purchase_order', 'ntp'])) {
        $stillAwarded = collect(['notice_of_award', 'purchase_order', 'ntp'])
            ->some(fn($d) => !empty($docs[$d]));

        $old_status = $rfq->status;

        if ($stillAwarded) {
            $rfq->update(['status' => 'Awarded']);
        } else {
            // No award docs remaining — revert based on actual pricing
            $allPriced = $rfq->items->every(fn($item) => !empty($item->unit_price));
            $rfq->update(['status' => $allPriced ? 'Quoted' : 'Received']);
        }

        if ($old_status !== $rfq->status) {
            ActivityLog::log('rfq.status_changed', $rfq, ['status' => $old_status], ['status' => $rfq->status]);
        }
    }
}

    // -------------------------------------------------------------------------
    // Save the date received for a specific document
    // Called when the user picks a date on a checked document
    // -------------------------------------------------------------------------
    public function setDocDate(int $rfqId, string $doc, string $date): void
    {
        $rfq  = Rfq::findOrFail($rfqId);
        $docs = $rfq->documents ?? [];

        $old_date = $docs[$doc]['date'] ?? null;
        // Keep received flag true and update only the date
        $docs[$doc] = ['received' => true, 'date' => $date];
        $rfq->update(['documents' => $docs]);

        ActivityLog::log('rfq.document_date_set', $rfq, ['doc' => $doc, 'date' => $old_date], ['doc' => $doc, 'date' => $date]);
    }

    // -------------------------------------------------------------------------
    // Computed metrics for the dashboard cards at the top of the tracker
    // Single query with conditional aggregation — replaces what used to be
    // 5 separate COUNT(*) queries (one per status), each of which scanned
    // the rfqs table. With the rfqs.status index added in the
    // 2026_06_09_144000_add_performance_indexes migration, this is now a
    // single index scan.
    // -------------------------------------------------------------------------
    public function getMetricsProperty(): array
    {
        $row = Rfq::selectRaw('
                COUNT(*) AS total,
                SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS quoted,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS awarded,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS declined
            ', ['Received', 'Reviewing', 'Quoted', 'Awarded', 'Declined'])
            ->first();

        $total    = (int) ($row->total    ?? 0);
        $pending  = (int) ($row->pending  ?? 0);
        $quoted   = (int) ($row->quoted   ?? 0);
        $awarded  = (int) ($row->awarded  ?? 0);
        $declined = (int) ($row->declined ?? 0);

        return [
            'total'    => $total,
            'pending'  => $pending,
            'quoted'   => $quoted,
            'awarded'  => $awarded,
            'declined' => $declined,
            'win_rate' => ($quoted + $awarded) > 0
                ? (int) round(($awarded / ($quoted + $awarded)) * 100)
                : 0,
        ];
    }
    // -------------------------------------------------------------------------
    // Render — builds the paginated, filtered, sorted RFQ list
    // -------------------------------------------------------------------------
    public function render()
{
    $rfqs = Rfq::with('agency', 'items')

        // Filter by search term — matches RFQ number or agency name
        ->when($this->search, function ($q) {
            $q->where('rfq_number', 'like', "%{$this->search}%")
              ->orWhereHas('agency', fn($a) =>
                  $a->where('name', 'like', "%{$this->search}%")
              );
        })

        // Filter by status tab if not showing all
        ->when($this->status !== 'all', fn($q) =>
            $q->where('status', $this->status)
        )

        // Sorting — agency requires a join, total_quoted requires a subquery sum,
        // all other columns can be sorted directly on the rfqs table
        ->when($this->sortBy === 'agency_id', function ($q) {
            $q->join('agencies', 'rfqs.agency_id', '=', 'agencies.id')
              ->orderBy('agencies.name', $this->sortDir)
              ->select('rfqs.*');
        })
        ->when($this->sortBy === 'total_quoted', function ($q) {
            $q->withSum('items', 'total_price')
              ->orderBy('items_sum_total_price', $this->sortDir);
        })
        ->when(!in_array($this->sortBy, ['agency_id', 'total_quoted']), function ($q) {
            $q->orderBy($this->sortBy, $this->sortDir);
        })

        ->paginate(15);

    return view('livewire.rfq-tracker', [
        'rfqs'    => $rfqs,
        'metrics' => $this->metrics,
    ]);
}
}
