@extends('layouts.app')

@section('content')
<div>
    {{-- Flash message --}}
    @if (session('message'))
        <div class="mb-4 bg-green-50 dark:bg-green-950 prime:bg-green-50 border border-green-200 dark:border-green-800 prime:border-green-200 text-green-800 dark:text-green-400 prime:text-green-800 text-sm px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="font-mono text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 mb-1">{{ $rfq->rfq_number }}</p>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $rfq->agency->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">{{ $rfq->agency->type }} · {{ $rfq->agency->region }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('rfqs.edit', $rfq) }}"
               class="text-sm border border-gray-200 dark:border-[var(--border)] prime:border-gray-200 dark:text-[var(--text-3)] prime:text-gray-500 dark:hover:text-[var(--text-1)] prime:hover:text-gray-900 dark:hover:border-[var(--accent)] prime:hover:border-green-400 px-4 py-2 rounded-lg text-gray-500 hover:text-gray-900 hover:border-gray-400 transition">
                Edit
            </a>
            <a href="{{ route('rfqs.index') }}"
               class="text-sm border border-gray-200 dark:border-[var(--border)] prime:border-gray-200 dark:text-[var(--text-3)] prime:text-gray-500 dark:hover:text-[var(--text-1)] prime:hover:text-gray-900 dark:hover:border-[var(--accent)] prime:hover:border-green-400 px-4 py-2 rounded-lg text-gray-500 hover:text-gray-900 hover:border-gray-400 transition">
                ← Back
            </a>
        </div>
    </div>

    {{-- Details --}}
    <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-gray-200 p-6 mb-4">
        <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide mb-4">RFQ Details</p>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 mb-1">Status</p>
                @php
                $colors = [
                    'Received'  => 'bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300 prime:bg-green-50 prime:text-green-700',
                    'Reviewing' => 'bg-amber-50 text-amber-800 dark:bg-amber-950 dark:text-amber-300 prime:bg-green-50 prime:text-green-700',
                    'Quoted'    => 'bg-green-50 text-green-800 dark:bg-green-950 dark:text-green-300 prime:bg-green-100 prime:text-green-800',
                    'Awarded'   => 'bg-teal-50 text-teal-800 dark:bg-teal-950 dark:text-teal-300 prime:bg-green-100 prime:text-green-800',
                    'Lost'      => 'bg-red-50 text-red-800 dark:bg-red-950 dark:text-red-400 prime:bg-green-50 prime:text-green-700',
                    'Declined'  => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 prime:bg-green-50 prime:text-green-700',
                ];
                @endphp
                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $colors[$rfq->status] ?? '' }}">
                    {{ $rfq->status }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 mb-1">Date Received</p>
                <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $rfq->date_received->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 mb-1">Deadline</p>
                @php $days = $rfq->days_left; @endphp
                <p class="text-sm font-medium {{ $days < 0 ? 'text-red-600' : ($days <= 3 ? 'text-amber-600' : 'text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900') }}">
                    {{ $rfq->deadline->format('M d, Y') }}
                    <span class="font-normal text-xs">
                        ({{ $days < 0 ? 'Overdue' : ($days === 0 ? 'Today' : $days . ' days left') }})
                    </span>
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 mb-1">ABC</p>
                <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">
                    {{ $rfq->abc ? '₱' . number_format($rfq->abc, 2) : '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 mb-1">PhilGEPS Ref</p>
                <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900 font-mono">{{ $rfq->philgeps_ref ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 mb-1">Procurement Mode</p>
                <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Small Value Procurement</p>
            </div>
        </div>
    </div>

    {{-- Line Items --}}
    @php
      $itemsJson = $rfq->items->map(fn($i) => [
    'brand'            => $i->brand,
    'item_description' => $i->item_description,
    'unit'             => $i->unit,
    'quantity'         => $i->quantity,
    'unit_price'       => $i->unit_price,
    'total_price'      => $i->total_price,
])->toJson();
    @endphp

    <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-gray-200 mb-4 overflow-hidden"
         x-data="itemSearch({{ $itemsJson }})"
         x-init="init()">

        <div class="px-6 py-4 border-b border-gray-100 dark:border-[var(--border)] prime:border-gray-200 flex items-center justify-between">
            <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide">
                Line Items
                <span class="ml-2 bg-gray-100 dark:bg-[var(--surface-3)] prime:bg-green-50 text-gray-600 dark:text-[var(--text-2)] prime:text-green-700 text-xs px-2 py-0.5 rounded-full"
                      x-text="search ? filtered.length + ' of {{ $rfq->items->count() }}' : '{{ $rfq->items->count() }}'">
                </span>
            </p>
        </div>

        <div class="px-6 py-3 border-b border-gray-100 dark:border-[var(--border)] prime:border-gray-200">
            <input x-model="search"
                   type="text"
                   placeholder="Search by description or unit..."
                   class="w-full border border-gray-900 dark:border-[var(--border)] prime:border-gray-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 dark:placeholder-[var(--text-3)] prime:placeholder-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-[var(--accent)] prime:focus:ring-green-200">
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-[var(--surface-2)] prime:bg-gray-50 border-b border-gray-100 dark:border-[var(--border)] prime:border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">#</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Item Description</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Brand</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Unit</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Qty</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Unit Price (₱)</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total (₱)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[var(--border)] prime:divide-gray-200">
                <template x-if="paged.length === 0">
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400">
                            <span x-show="search">No items match "<span x-text="search" class="font-medium"></span>".</span>
                            <span x-show="!search">No items added yet.</span>
                        </td>
                    </tr>
                </template>
                <template x-for="(item, i) in paged" :key="i">
                    <tr class="hover:bg-gray-50 dark:hover:bg-[var(--surface-2)] prime:hover:bg-green-50">
                        <td class="px-6 py-3 text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 text-xs" x-text="(page - 1) * perPage + i + 1"></td>
                        <td class="px-6 py-3 font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900" x-text="item.item_description"></td>
                        <td class="px-6 py-3 text-gray-500 dark:text-gray-400 prime:text-gray-500" x-text="item.brand || '—'"></td>
                        <td class="px-6 py-3 text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500" x-text="item.unit"></td>
                        <td class="px-6 py-3 text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500" x-text="Number(item.quantity).toLocaleString()"></td>
                        <td class="px-6 py-3 text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900"
                            x-text="item.unit_price ? '₱' + Number(item.unit_price).toLocaleString('en-PH', {minimumFractionDigits:2}) : '—'">
                        </td>
                        <td class="px-6 py-3 font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900"
                            x-text="item.total_price ? '₱' + Number(item.total_price).toLocaleString('en-PH', {minimumFractionDigits:2}) : '—'">
                        </td>
                    </tr>
                </template>
            </tbody>

            @if ($rfq->items->count() > 0)
                <tfoot class="bg-gray-50 dark:bg-[var(--surface-2)] prime:bg-gray-50 border-t border-gray-200 dark:border-[var(--border)] prime:border-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total Quoted</td>
                        <td class="px-6 py-3 font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">
                            ₱{{ number_format($rfq->total_quoted, 2) }}
                        </td>
                    </tr>
                    @if ($rfq->abc)
                        <tr>
                            <td colspan="6" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">ABC Remaining</td>
                            @php $remaining = $rfq->abc - $rfq->total_quoted; @endphp
                            <td class="px-6 py-3 font-semibold {{ $remaining < 0 ? 'text-red-600' : 'text-green-700 dark:text-green-400 prime:text-green-700' }}">
                                ₱{{ number_format(abs($remaining), 2) }}
                                {{ $remaining < 0 ? '(over budget)' : '' }}
                            </td>
                        </tr>
                    @endif
                </tfoot>
            @endif
        </table>
<div x-show="totalPages > 1 || true" class="px-6 py-3 border-t border-gray-100 dark:border-[var(--border)] prime:border-gray-200 flex items-center justify-between text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">
    <div class="flex items-center gap-2">
        <span class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400">Rows</span>
     <template x-for="size in [5, 10, 20]" :key="size">
    <button @click="perPage = size; page = 1; applyFilter()"
            :disabled="filtered.length <= (size === 10 ? 5 : size === 20 ? 10 : 0) && perPage !== size"
            :class="perPage === size
                ? 'bg-gray-900 dark:bg-red-900 prime:bg-green-600 text-white border-transparent'
                : 'border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 text-gray-500 dark:text-gray-400 prime:text-gray-500 hover:bg-gray-50 dark:hover:bg-[#2a2a2a] prime:hover:bg-green-50'"
            class="px-2.5 py-1 border rounded-lg text-xs transition disabled:opacity-40 disabled:cursor-not-allowed"
            x-text="size">
    </button>
</template>
        <span class="text-xs ml-1" x-text="'Page ' + page + ' of ' + totalPages"></span>
    </div>
    <div class="flex gap-2">
        <button @click="prevPage" :disabled="page <= 1"
                class="px-3 py-1.5 border border-gray-200 dark:border-[var(--border)] prime:border-gray-200 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 rounded-lg text-xs hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
            ← Prev
        </button>
      <button @click="nextPage" :disabled="page >= totalPages || paged.length < perPage"
        class="px-3 py-1.5 border border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 dark:hover:bg-[#2a2a2a] prime:hover:bg-green-50 rounded-lg text-xs hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
    Next →
</button>
    </div>
</div>
    {{-- Notes --}}
    @if ($rfq->notes)
        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-gray-200 p-6 mb-4">
            <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide mb-2">Notes</p>
            <p class="text-sm text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900">{{ $rfq->notes }}</p>
        </div>
    @endif

    {{-- Danger zone --}}
    <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-red-100 dark:border-red-900 prime:border-red-200 p-6">
        <p class="text-xs font-medium text-red-400 dark:text-red-400 prime:text-red-700 uppercase tracking-wide mb-3">Danger Zone</p>
        <div class="flex items-center gap-3">
            @if(!in_array($rfq->status, ['Awarded', 'Lost', 'Declined']))
            <form id="decline-form-{{ $rfq->id }}" action="{{ route('rfqs.decline', $rfq) }}" method="POST" style="display:none">
                @csrf
            </form>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-confirm', {detail: {title: 'Decline RFQ?', message: 'Mark {{ $rfq->rfq_number }} as Declined?', action: () => document.getElementById('decline-form-{{ $rfq->id }}').submit()}}))"
                    class="text-sm text-orange-600 dark:text-orange-400 prime:text-orange-500 border border-orange-200 dark:border-orange-900 prime:border-orange-400 px-4 py-2 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-950 prime:hover:bg-orange-50 transition">
                Decline
            </button>
            @endif
            <form id="delete-form-{{ $rfq->id }}" action="{{ route('rfqs.destroy', $rfq) }}" method="POST" style="display:none">
                @csrf
                @method('DELETE')
            </form>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-confirm', {detail: {title: 'Delete RFQ?', message: 'Are you sure you want to delete {{ $rfq->rfq_number }}?', action: () => document.getElementById('delete-form-{{ $rfq->id }}').submit()}}))"
                    class="text-sm text-red-600 dark:text-red-400 prime:text-red-400 border border-red-200 dark:border-red-900 prime:border-red-400 px-4 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-950 prime:hover:bg-red-50 transition">
                Delete this RFQ
            </button>
        </div>
    </div>

</div>

<script>
function itemSearch(allItems) {
    return {
        all: allItems,
        filtered: allItems,
        paged: [],
        search: '',
        page: 1,
        perPage: 5,
        totalPages: 1,

        init() {
            this.$watch('search', () => {
                this.page = 1;
                this.applyFilter();
            });
            this.applyFilter();
        },

        applyFilter() {
            const q = this.search.toLowerCase().trim();
            this.filtered = q
    ? this.all.filter(i =>
        i.item_description.toLowerCase().includes(q) ||
        i.unit.toLowerCase().includes(q) ||
        (i.brand && i.brand.toLowerCase().includes(q))
      )
    : [...this.all];
            this.totalPages = Math.max(1, Math.ceil(this.filtered.length / this.perPage));
            this.paginate();
        },

        paginate() {
            const start = (this.page - 1) * this.perPage;
            this.paged = this.filtered.slice(start, start + this.perPage);
        },

        nextPage() {
            if (this.page < this.totalPages) { this.page++; this.paginate(); }
        },

        prevPage() {
            if (this.page > 1) { this.page--; this.paginate(); }
        },
    };
}
</script>
@endsection