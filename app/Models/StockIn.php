<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $fillable = [
        'qty',
        'detail',
        'product_id',
        'supplier_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
}
