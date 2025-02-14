<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $fillable = [
        'name',
        'product_id',
        'detail',
        'qty',
    ];

    public function products(){
        return $this->belongsTo(Product::class);
    }
}
