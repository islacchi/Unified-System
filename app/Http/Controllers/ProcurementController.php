<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\ActivityLog;
use App\Exports\ProcurementQuotationExport;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function create()
    {
        return view('procurements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'procurement_number' => 'nullable|string|unique:procurements,procurement_number',
            'prepared_by' => 'required|exists:users,id',
            'date_prepared' => 'required|date',
            'delivery_deadline' => 'nullable|date|after_or_equal:date_prepared',
            'status' => 'required|in:Draft,Submitted,Approved,Ordered,Delivered,Cancelled',
            'notes' => 'nullable|string',
        ]);

        $procurement = Procurement::create($validated);
        ActivityLog::log('procurement.created', $procurement);

        return redirect()->route('procurements.index')
            ->with('message', "Procurement {$procurement->procurement_number} created successfully.");
    }

    public function show(Procurement $procurement)
    {
        return view('procurements.show', compact('procurement'));
    }

    public function edit(Procurement $procurement)
    {
        if ($procurement->status !== 'Draft') {
            return redirect()->route('procurements.show', $procurement)
                ->with('error', 'Only drafts can be edited.');
        }

        return view('procurements.edit', compact('procurement'));
    }

    public function update(Request $request, Procurement $procurement)
    {
        if ($procurement->status !== 'Draft') {
            return redirect()->route('procurements.show', $procurement)
                ->with('error', 'Only drafts can be edited.');
        }

        $validated = $request->validate([
            'prepared_by' => 'required|exists:users,id',
            'date_prepared' => 'required|date',
            'delivery_deadline' => 'nullable|date|after_or_equal:date_prepared',
            'status' => 'required|in:Draft,Submitted,Approved,Ordered,Delivered,Cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $procurement->status;
        $procurement->update($validated);

        if ($oldStatus !== $procurement->status) {
            ActivityLog::log('procurement.status_changed', $procurement, ['status' => $oldStatus], ['status' => $procurement->status]);
        } else {
            ActivityLog::log('procurement.updated', $procurement);
        }

        return redirect()->route('procurements.show', $procurement)
            ->with('message', "Procurement {$procurement->procurement_number} updated successfully.");
    }

    public function destroy(Procurement $procurement)
    {
        if ($procurement->status !== 'Draft') {
            return redirect()->route('procurements.index')
                ->with('error', 'Only drafts can be deleted.');
        }

        $procurement->delete();
        ActivityLog::log('procurement.deleted', $procurement);

        return redirect()->route('procurements.index')
            ->with('message', "Procurement {$procurement->procurement_number} deleted successfully.");
    }

    public function print(Procurement $procurement)
    {
        return view('procurements.print', compact('procurement'));
    }

    public function export(Procurement $procurement)
    {
        $export = new ProcurementQuotationExport($procurement);
        $content = $export->generate();
        $filename = $export->fileName();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
