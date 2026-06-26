<?php

namespace App\Exports;

use App\Models\Procurement;

class ProcurementQuotationExport
{
    public function __construct(protected Procurement $procurement)
    {
    }

    public function generate(): string
    {
        $items = $this->procurement->items;
        $grouped = $items->groupBy(fn($item) => $item->item_description . '|' . ($item->brand ?? '') . '|' . $item->unit);

        $html = '<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;font-family:Arial;font-size:11px;">';

        // Header row
        $html .= '<thead>';
        $html .= '<tr style="background:#f0f0f0;font-weight:bold;">';
        $html .= '<th>#</th>';
        $html .= '<th>Description</th>';
        $html .= '<th>Brand</th>';
        $html .= '<th>Agency</th>';
        $html .= '<th>Unit</th>';
        $html .= '<th>Qty</th>';
        $html .= '<th>Unit Price</th>';
        $html .= '<th>Subtotal</th>';
        $html .= '<th>Total</th>';
        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';

        $rowNum = 0;
        foreach ($grouped as $groupItems) {
            $rowNum++;
            $first = $groupItems->first();
            $count = $groupItems->count();
            $totalPrice = $groupItems->sum('total_price');
            $allSamePrice = $groupItems->pluck('unit_price')->unique()->filter()->count() <= 1;
            $totalQty = $groupItems->sum('quantity');

            foreach ($groupItems as $i => $gi) {
                $html .= '<tr>';
                $html .= $i === 0 ? '<td rowspan="' . $count . '">' . $rowNum . '</td>' : '';
                $html .= $i === 0 ? '<td rowspan="' . $count . '">' . htmlspecialchars($first->item_description) . '</td>' : '';
                $html .= $i === 0 ? '<td rowspan="' . $count . '">' . htmlspecialchars($first->brand ?? '-') . '</td>' : '';
                $html .= '<td>' . htmlspecialchars($gi->agency->name ?? 'N/A') . '</td>';
                $html .= $i === 0 ? '<td rowspan="' . $count . '">' . htmlspecialchars($first->unit) . '</td>' : '';
                $html .= '<td align="right">' . number_format($gi->quantity, 0) . '</td>';
                $html .= ($i === 0 && $allSamePrice) || (!$allSamePrice)
                    ? '<td align="right"' . ($i === 0 && $allSamePrice ? ' rowspan="' . $count . '"' : '') . '>₱ ' . number_format($gi->unit_price, 2) . '</td>'
                    : '';
                $html .= '<td align="right">₱ ' . number_format($gi->total_price, 2) . '</td>';
                $html .= $i === 0 ? '<td rowspan="' . $count . '" align="right" style="font-weight:bold;">₱ ' . number_format($totalPrice, 2) . '</td>' : '';
                $html .= '</tr>';
            }
        }

        $html .= '</tbody>';

        // Summary row
        $html .= '<tfoot>';
        $html .= '<tr style="font-weight:bold;background:#f0f0f0;">';
        $html .= '<td colspan="8" align="right">TOTAL AMOUNT:</td>';
        $html .= '<td align="right">₱ ' . number_format($this->procurement->total_amount, 2) . '</td>';
        $html .= '</tr>';
        $html .= '</tfoot>';

        $html .= '</table>';

        // Full HTML page for excel
        $fullHtml = '
        <html xmlns:o="urn:schemas-microsoft-com:office:office"
              xmlns:x="urn:schemas-microsoft-com:office:excel"
              xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="UTF-8">
            <!--[if gte mso 9]>
            <xml>
                <x:ExcelWorkbook>
                    <x:ExcelWorksheets>
                        <x:ExcelWorksheet>
                            <x:Name>Quotation</x:Name>
                            <x:WorksheetOptions>
                                <x:DisplayGridlines/>
                            </x:WorksheetOptions>
                        </x:ExcelWorksheet>
                    </x:ExcelWorksheets>
                </x:ExcelWorkbook>
            </xml>
            <![endif]-->
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #999; padding: 4px; font-size: 10pt; }
                th { background: #e0e0e0; font-weight: bold; text-align: left; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <h2 style="text-align:center;">Purchase Quotation</h2>
            <p style="text-align:center;font-size:11px;color:#666;">' . htmlspecialchars($this->procurement->procurement_number) . '</p>

            <hr style="border:1px solid #222;">

            <table border="0" style="border:none;width:100%;margin-bottom:10px;">
                <tr>
                    <td style="border:none;"><strong>Reference No.:</strong> ' . htmlspecialchars($this->procurement->procurement_number) . '</td>
                    <td style="border:none;"><strong>Status:</strong> ' . htmlspecialchars($this->procurement->status) . '</td>
                </tr>
                <tr>
                    <td style="border:none;"><strong>Agency/ies:</strong> ' . htmlspecialchars($items->unique('agency_id')->pluck('agency.name')->filter()->join(', ') ?: 'N/A') . '</td>
                    <td style="border:none;"><strong>Prepared By:</strong> ' . htmlspecialchars($this->procurement->preparedBy?->name ?? 'N/A') . '</td>
                </tr>
                <tr>
                    <td style="border:none;"><strong>Date:</strong> ' . $this->procurement->date_prepared->format('F d, Y') . '</td>
                    <td style="border:none;"><strong>Delivery Deadline:</strong> ' . ($this->procurement->delivery_deadline?->format('F d, Y') ?? 'N/A') . '</td>
                </tr>
            </table>

            <h3>Items</h3>
            ' . $html . '
        </body>
        </html>';

        return $fullHtml;
    }

    public function fileName(): string
    {
        return 'quotation-' . str_replace('/', '-', $this->procurement->procurement_number) . '.xls';
    }
}