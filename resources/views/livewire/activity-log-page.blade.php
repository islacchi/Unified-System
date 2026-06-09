<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Activity Log</h1>
            <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">Audit trail of all system changes</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3 mb-4">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   placeholder="Search activity..."
                   class="w-full pl-4 pr-4 py-2 text-sm border border-gray-200 dark:border-[var(--border)] dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:border-gray-200 prime:placeholder-gray-400 prime:text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
        </div>
        <div class="relative min-w-[180px]">
            <select wire:model.live="actionFilter"
                    class="w-full appearance-none pl-4 pr-8 py-2 text-sm border border-gray-200 dark:border-[var(--border)] dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:border-gray-200 prime:text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                <option value="">All actions</option>
                @foreach ($availableActions as $action)
                    <option value="{{ $action }}">{{ $action }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-400 dark:text-[var(--text-3)]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" style="table-layout: fixed; min-width: 750px">
                <thead class="bg-gray-50 dark:bg-[var(--surface-2)] prime:bg-gray-50 border-b border-gray-200 dark:border-[var(--border)] prime:border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 w-44">Date / Time</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 w-36">User</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 w-44">Action</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-[var(--border)] prime:divide-gray-200">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                            <td class="px-4 py-3 text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 whitespace-nowrap">
                                {{ $log->created_at->format('M d, Y') }}
                                <span class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 ml-1">{{ $log->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">
                                    {{ $log->user?->name ?? 'System' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $actionColor = match(true) {
                                        str_contains($log->action, 'created') => 'bg-green-50 text-green-800 dark:bg-green-950 dark:text-green-300 prime:bg-green-50 prime:text-green-800',
                                        str_contains($log->action, 'updated') || str_contains($log->action, 'status_changed') || str_contains($log->action, 'document') => 'bg-amber-50 text-amber-800 dark:bg-amber-950 dark:text-amber-300 prime:bg-amber-50 prime:text-amber-800',
                                        str_contains($log->action, 'deleted') => 'bg-red-50 text-red-800 dark:bg-red-950 dark:text-red-400 prime:bg-red-50 prime:text-red-800',
                                        str_contains($log->action, 'declined') => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 prime:bg-gray-100 prime:text-gray-700',
                                        default => 'bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300 prime:bg-blue-50 prime:text-blue-800',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $actionColor }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-[var(--text-2)] prime:text-gray-700" style="overflow-wrap: anywhere">
                                {{ $log->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-sm text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400">
                                No activity recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-[var(--border)] prime:border-gray-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>