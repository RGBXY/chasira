<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $fillable = [
        'qty',
        'detail',
        'product_id',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
