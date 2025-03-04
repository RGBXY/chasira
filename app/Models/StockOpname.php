<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = [
        'qty',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
