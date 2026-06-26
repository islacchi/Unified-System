<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurement Quotation - {{ $procurement->procurement_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; line-height: 1.5; color: #333; padding: 20px; }
        .container { max-width: 850px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 18px; border-bottom: 2px solid #222; padding-bottom: 12px; }
        .header h1 { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header p { font-size: 10px; color: #666; margin-top: 3px; }
        .section { margin-bottom: 16px; }
        .section-title { font-size: 10px; font-weight: 600; text-transform: uppercase; color: #555; margin-bottom: 6px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; }
        .info-item { margin-bottom: 2px; }
        .info-item label { font-weight: 600; font-size: 9px; color: #666; display: block; }
        .info-item span { font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f5f5f5; font-weight: 600; text-align: left; padding: 5px 4px; border: 1px solid #bbb; font-size: 9px; text-transform: uppercase; }
        td { padding: 4px; border: 1px solid #ccc; font-size: 10px; vertical-align: top; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .tfoot td { font-weight: 600; background: #f5f5f5; border: 1px solid #bbb; font-size: 10px; }
        .signature { margin-top: 35px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .signature-box { border-top: 1px solid #333; padding-top: 6px; text-align: center; }
        .signature-box p { font-size: 9px; color: #666; margin-top: 3px; }
        .footer { margin-top: 25px; padding-top: 8px; border-top: 1px solid #ccc; font-size: 9px; color: #666; text-align: center; }
        @media print { body { padding: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Purchase Quotation</h1>
            <p>{{ $procurement->procurement_number }}</p>
            <div class="no-print" style="margin-top: 8px;">
                <a href="{{ route('procurements.export', $procurement) }}"
                   style="display:inline-block; padding:6px 14px; border:1px solid #333; text-decoration:none; font-size:10px; color:#222; border-radius:4px;">
                    📥 Download Excel
                </a>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Details</div>
            <div class="info-grid">
                <div class="info-item">
                    <label>REFERENCE NO.</label>
                    <span>{{ $procurement->procurement_number }}</span>
                </div>
                <div class="info-item">
                    <label>STATUS</label>
                    <span>{{ $procurement->status }}</span>
                </div>
                <div class="info-item">
                    <label>AGENCY/IES</label>
                    <span>{{ $procurement->items->unique('agency_id')->pluck('agency.name')->filter()->join(', ') ?: 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>PREPARED BY</label>
                    <span>{{ $procurement->preparedBy?->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>DATE</label>
                    <span>{{ $procurement->date_prepared->format('F d, Y') }}</span>
                </div>
                <div class="info-item">
                    <label>DELIVERY DEADLINE</label>
                    <span>{{ $procurement->delivery_deadline?->format('F d, Y') ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Items</div>
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">#</th>
                        <th style="width: 22%;">Description</th>
                        <th style="width: 11%;">Brand</th>
                        <th style="width: 14%;">Agency</th>
                        <th class="text-center" style="width: 5%;">Unit</th>
                        <th class="text-right" style="width: 9%;">Qty</th>
                        <th class="text-right" style="width: 13%;">Unit Price</th>
                        <th class="text-right" style="width: 13%;">Subtotal</th>
                        <th class="text-right" style="width: 10%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grouped = $procurement->items->groupBy(fn($item) => $item->item_description . '|' . ($item->brand ?? '') . '|' . $item->unit);
                    @endphp
                    @foreach($grouped as $groupItems)
                        @php
                            $first = $groupItems->first();
                            $count = $groupItems->count();
                            $totalPrice = $groupItems->sum('total_price');
                            $allSamePrice = $groupItems->pluck('unit_price')->unique()->filter()->count() <= 1;
                        @endphp

                        <tr>
                            <td class="text-center" rowspan="{{ $count }}">{{ $loop->iteration }}</td>
                            <td rowspan="{{ $count }}">{{ $first->item_description }}</td>
                            <td rowspan="{{ $count }}">{{ $first->brand ?? '-' }}</td>
                            <td>{{ $groupItems[0]->agency->name ?? 'N/A' }}</td>
                            <td class="text-center" rowspan="{{ $count }}">{{ $first->unit }}</td>
                            <td class="text-right">{{ number_format($groupItems[0]->quantity, 0) }}</td>
                            <td class="text-right" rowspan="{{ $allSamePrice ? $count : 1 }}">₱ {{ number_format($groupItems[0]->unit_price, 2) }}</td>
                            <td class="text-right">₱ {{ number_format($groupItems[0]->total_price, 2) }}</td>
                            <td class="text-right" rowspan="{{ $count }}" style="font-weight:600;">₱ {{ number_format($totalPrice, 2) }}</td>
                        </tr>

                        @for($i = 1; $i < $count; $i++)
                            <tr>
                                <td>{{ $groupItems[$i]->agency->name ?? 'N/A' }}</td>
                                <td class="text-right">{{ number_format($groupItems[$i]->quantity, 0) }}</td>
                                @if(!$allSamePrice)
                                    <td class="text-right">₱ {{ number_format($groupItems[$i]->unit_price, 2) }}</td>
                                @endif
                                <td class="text-right">₱ {{ number_format($groupItems[$i]->total_price, 2) }}</td>
                            </tr>
                        @endfor
                    @endforeach
                </tbody>
                <tfoot class="tfoot">
                    <tr>
                        <td colspan="8" class="text-right">TOTAL AMOUNT:</td>
                        <td class="text-right">₱ {{ number_format($procurement->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="signature">
            <div class="signature-box">
                <p>Prepared By:</p>
                <p style="margin-top: 35px; font-weight: bold;">{{ $procurement->preparedBy?->name ?? 'N/A' }}</p>
                <p>Date: {{ $procurement->date_prepared->format('F d, Y') }}</p>
            </div>
            <div class="signature-box">
                <p>Approved By:</p>
                <p style="margin-top: 35px; font-weight: bold;">_______________________</p>
                <p>Date: _______________</p>
            </div>
        </div>

        <div class="footer">
            <p>Generated on {{ now()->format('F d, Y h:i A') }} | {{ $procurement->procurement_number }}</p>
        </div>
    </div>
</body>
</html>