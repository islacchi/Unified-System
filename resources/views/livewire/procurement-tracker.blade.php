<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Procurement Tracker</h1>
            <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">Manage and track all procurement orders</p>
        </div>
        <a href="{{ route('procurements.create') }}"
           class="text-sm px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition font-medium">
            + New Procurement
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" wire:model.live="search" placeholder="Search procurement #"
                       class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
            </div>
            <div>
                <select wire:model.live="statusFilter"
                        class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="text" wire:model.live="itemSearch" placeholder="Search item name or brand..."
                       class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
            </div>
            <div>
                <input type="date" wire:model.live="dateFrom" placeholder="From date"
                       class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
            </div>
            <div>
                <input type="date" wire:model.live="dateTo" placeholder="To date"
                       class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Procurement #</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Date Prepared</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Delivery Deadline</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total Amount</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Items</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($procurements as $procurement)
                        <tr class="border-b border-gray-100 dark:border-[var(--border)] prime:border-green-100 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                            <td class="py-3 px-4 font-mono text-xs">
                                <a href="{{ route('procurements.show', $procurement) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $procurement->procurement_number }}
                                </a>
                            </td>
                            <td class="py-3 px-4">{{ $procurement->date_prepared->format('M d, Y') }}</td>
                            <td class="py-3 px-4">{{ $procurement->delivery_deadline?->format('M d, Y') ?? 'N/A' }}</td>
                            <td class="py-3 px-4 font-mono">₱ {{ number_format($procurement->total_amount, 2) }}</td>
                            <td class="py-3 px-4">
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
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$procurement->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $procurement->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">{{ $procurement->items->count() }}</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('procurements.show', $procurement) }}"
                                       class="text-gray-600 dark:text-[var(--text-2)] prime:text-gray-600 hover:text-gray-900 dark:hover:text-[var(--text-1)] prime:hover:text-gray-900 transition">
                                        View
                                    </a>
                                    @if($procurement->status === 'Draft')
                                        <a href="{{ route('procurements.edit', $procurement) }}"
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition">
                                            Edit
                                        </a>
                                        <button wire:click="delete({{ $procurement->id }})" wire:confirm="Are you sure you want to delete this procurement?"
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition">
                                            Delete
                                        </button>
                                    @endif
                                    <a href="{{ route('procurements.print', $procurement) }}"
                                       class="text-gray-600 dark:text-[var(--text-2)] prime:text-gray-600 hover:text-gray-900 dark:hover:text-[var(--text-1)] prime:hover:text-gray-900 transition"
                                       target="_blank">
                                        Print
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">
                                No procurements found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-200 dark:border-[var(--border)] prime:border-green-900">
            {{ $procurements->links() }}
        </div>
    </div>
</div>