<?php

namespace App\Http\Controllers;

use App\Models\Rfq;
use App\Models\Agency;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RfqController extends Controller
{
    /**
     * Show the form for creating a new RFQ.
     * Loads all agencies sorted by name for the dropdown.
     */
    public function create()
    {
        $agencies = Agency::orderBy('name')->get();
        return view('rfqs.create', compact('agencies'));
    }

    /**
     * Store a newly created RFQ in the database.
     * Auto-generates an RFQ number if the user left it blank.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rfq_number'    => 'nullable|string|unique:rfqs,rfq_number',
            'agency_id'     => 'required|exists:agencies,id',
            'date_received' => 'required|date',
            'deadline'      => 'required|date|after_or_equal:date_received',
            'abc'           => 'nullable|numeric|min:0',
            'status'        => 'required|in:Received,Reviewing,Lost,Declined',
            'notes'         => 'nullable|string',
            'philgeps_ref'  => 'nullable|string',
        ]);

        // Auto-generate RFQ number if not provided
        if (empty($validated['rfq_number'])) {
            $validated['rfq_number'] = Rfq::generateNumber();
        }

        $rfq = Rfq::create($validated);

        ActivityLog::log('rfq.created', $rfq);

        return redirect()->route('rfqs.index')
                         ->with('message', "RFQ {$rfq->rfq_number} created successfully.");
    }

    /**
     * Display the details of a specific RFQ.
     * Automatically transitions status from Received to Reviewing
     * on first view, indicating the RFQ is being evaluated.
     */
    public function show(Rfq $rfq)
    {
        if ($rfq->status === 'Received') {
            $old = ['status' => 'Received'];
            $rfq->update(['status' => 'Reviewing']);
            ActivityLog::log('rfq.status_changed', $rfq, $old, ['status' => 'Reviewing'], "Changed status of RFQ #{$rfq->rfq_number} from Received to Reviewing");
        }

        return view('rfqs.show', compact('rfq'));
    }

    /**
     * Show the form for editing an existing RFQ.
     * The RFQ is passed via route model binding.
     */
    public function edit(Rfq $rfq)
    {
        return view('rfqs.edit', compact('rfq'));
    }

    /**
     * Update an existing RFQ in the database.
     * RFQ number is excluded from updates to prevent changing it after creation.
     */
    public function update(Request $request, Rfq $rfq)
    {
        $validated = $request->validate([
            'agency_id'     => 'required|exists:agencies,id',
            'date_received' => 'required|date',
            'deadline'      => 'required|date|after_or_equal:date_received',
            'abc'           => 'nullable|numeric|min:0',
            'status'        => 'required|in:Received,Reviewing,Lost,Declined',
            'notes'         => 'nullable|string',
            'philgeps_ref'  => 'nullable|string',
        ]);

        $oldStatus = $rfq->status;
        $rfq->update($validated);

        if ($oldStatus !== $rfq->status) {
            ActivityLog::log('rfq.status_changed', $rfq, ['status' => $oldStatus], ['status' => $rfq->status]);
        } else {
            ActivityLog::log('rfq.updated', $rfq);
        }

        return redirect()->route('rfqs.show', $rfq)
                         ->with('message', "RFQ {$rfq->rfq_number} updated successfully.");
    }

    /**
     * Mark an RFQ as Declined.
     * Similar to "Lost" — the RFQ is no longer active.
     * Cannot be declined if already Awarded or Lost.
     */
    public function decline(Rfq $rfq)
    {
        if (in_array($rfq->status, ['Awarded', 'Lost', 'Declined'])) {
            return redirect()->route('rfqs.show', $rfq)
                             ->with('message', "Cannot decline an RFQ with status \"{$rfq->status}\".");
        }

        $old = ['status' => $rfq->status];
        $rfq->update(['status' => 'Declined']);
        ActivityLog::log('rfq.declined', $rfq, $old, ['status' => 'Declined']);

        return redirect()->route('rfqs.show', $rfq)
                         ->with('message', "RFQ {$rfq->rfq_number} marked as Declined.");
    }

    /**
     * Delete an RFQ and its associated items from the database.
     * Redirects back to the RFQ tracker list after deletion.
     */
    public function destroy(Rfq $rfq)
    {
        $label = $rfq->rfq_number;
        $rfq->delete();
        ActivityLog::log('rfq.deleted', null, ['rfq_number' => $label], null, "Deleted RFQ #{$label}");
        return redirect()->route('rfqs.index')
                         ->with('message', 'RFQ deleted successfully.');
    }
    public function print(Rfq $rfq)
{
    $rfq->load('agency', 'items');
    return view('rfqs.print', compact('rfq'));
}
}