<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procurement extends Model
{
    protected $fillable = [
        'procurement_number',
        'prepared_by',
        'date_prepared',
        'delivery_deadline',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'date_prepared' => 'date',
        'delivery_deadline' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProcurementItem::class);
    }

    public function agencies()
    {
        return $this->belongsToMany(Agency::class, 'procurement_items');
    }

    public static function generateNumber(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)
            ->orderByRaw('CAST(SUBSTRING_INDEX(procurement_number, "-", -1) AS UNSIGNED) DESC')
            ->value('procurement_number');

        $next = $last ? (int) explode('-', $last)[2] + 1 : 1;

        return sprintf('PRC-%d-%03d', $year, $next);
    }
}