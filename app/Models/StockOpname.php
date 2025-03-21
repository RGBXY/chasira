<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = [
        'product_id',
        'actual_stock',
        'system_stock',
        'discrepancy_stock',
        'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
