@extends('layouts.app')

@section('content')
    <div>
        {{-- Page header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Procurement Details</h1>
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">{{ $procurement->procurement_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('procurements.print', $procurement) }}"
                   target="_blank"
                   class="text-sm px-4 py-2 rounded-lg border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                    🖨 Print Quotation
                </a>
                <a href="{{ route('procurements.export', $procurement) }}"
                   class="text-sm px-4 py-2 rounded-lg border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                    📥 Download Excel
                </a>
                <a href="{{ route('procurements.index') }}"
                   class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 hover:text-gray-900 dark:hover:text-[var(--text-1)] prime:hover:text-gray-900 border border-gray-200 dark:border-[var(--border)] prime:border-green-900 px-4 py-2 rounded-lg transition">
                    ← Back
                </a>
            </div>
        </div>

        {{-- Status --}}
        <div class="mb-6">
            @php
                $statusColors = [
                    'Draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 prime:bg-gray-100 prime:text-gray-800',
                    'Submitted' => 'bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300 prime:bg-blue-50 prime:text-blue-800',
                    'Approved' => 'bg-green-50 text-green-800 dark:bg-green-950 dark:text-green-300 prime:bg-green-50 prime:text-green-800',
                    'Ordered' => 'bg-purple-50 text-purple-800 dark:bg-purple-950 dark:text-purple-300 prime:bg-purple-50 prime:text-purple-800',
                    'Delivered' => 'bg-teal-50 text-teal-800 dark:bg-teal-950 dark:text-teal-300 prime:bg-teal-50 prime:text-teal-800',
                    'Cancelled' => 'bg-red-50 text-red-800 dark:bg-red-950 dark:text-red-400 prime:bg-red-50 prime:text-red-700',
                ];
            @endphp
            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusColors[$procurement->status] ?? 'bg-gray-100 text-gray-600' }}">
                {{ $procurement->status }}
            </span>
        </div>

        {{-- Procurement Info --}}
        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6 mb-6">
            <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide mb-4">Procurement Information</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Procurement Number</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $procurement->procurement_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Prepared By</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $procurement->preparedBy?->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Date Prepared</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $procurement->date_prepared->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Delivery Deadline</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $procurement->delivery_deadline?->format('F d, Y') ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total Amount</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">₱ {{ number_format($procurement->total_amount, 2) }}</p>
                </div>
                @if($procurement->notes)
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Notes</p>
                        <p class="text-sm text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $procurement->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6">
            @php
                $itemStatusColors = [
                    'Pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 prime:bg-gray-100 prime:text-gray-800',
                    'Ordered' => 'bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300 prime:bg-blue-50 prime:text-blue-800',
                    'Delivered' => 'bg-purple-50 text-purple-800 dark:bg-purple-950 dark:text-purple-300 prime:bg-purple-50 prime:text-purple-800',
                    'Received' => 'bg-green-50 text-green-800 dark:bg-green-950 dark:text-green-300 prime:bg-green-50 prime:text-green-800',
                    'Cancelled' => 'bg-red-50 text-red-800 dark:bg-red-950 dark:text-red-400 prime:bg-red-50 prime:text-red-700',
                ];
            @endphp

            <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide mb-4">Items ({{ $procurement->items->count() }})</p>

            @if($procurement->items->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Agency</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Description</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Unit</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Qty</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Unit Price</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($procurement->items as $item)
                                <tr class="border-b border-gray-100 dark:border-[var(--border)] prime:border-green-100">
                                    <td class="py-2 px-2">{{ $item->agency->name ?? 'N/A' }}</td>
                                    <td class="py-2 px-2">{{ $item->item_description }}</td>
                                    <td class="py-2 px-2">{{ $item->unit }}</td>
                                    <td class="py-2 px-2 text-right">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="py-2 px-2 text-right">{{ $item->unit_price ? '₱ ' . number_format($item->unit_price, 2) : '-' }}</td>
                                    <td class="py-2 px-2 text-right font-mono">₱ {{ number_format($item->total_price, 2) }}</td>
                                    <td class="py-2 px-2">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $itemStatusColors[$item->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                                <td colspan="5" class="py-3 px-2 text-right font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Total Amount:</td>
                                <td class="py-3 px-2 text-right font-mono font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">
                                    ₱ {{ number_format($procurement->total_amount, 2) }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 text-center py-8">No items in this procurement.</p>
            @endif
        </div>

        @if($procurement->status === 'Draft')
            <div class="mt-6 flex items-center gap-3">
                <a href="{{ route('procurements.edit', $procurement) }}"
                   class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm font-medium">
                    Edit Procurement
                </a>
            </div>
        @endif
    </div>
@endsection