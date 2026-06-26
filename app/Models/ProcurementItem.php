<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementItem extends Model
{
    protected $fillable = [
        'procurement_id',
        'agency_id',
        'rfq_item_id',
        'brand',
        'item_description',
        'unit',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function procurement(): BelongsTo
    {
        return $this->belongsTo(Procurement::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function rfqItem(): BelongsTo
    {
        return $this->belongsTo(RfqItem::class);
    }

    protected static function booted(): void
    {
        static::saving(function (ProcurementItem $item) {
            if ($item->unit_price && $item->quantity) {
                $item->total_price = $item->unit_price * $item->quantity;
            }
        });
    }
}