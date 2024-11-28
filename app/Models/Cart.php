<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'chasier_id',
        'product_id',
        'qty',
        'price'
    ];

    public function product(){
       return $this->belongsTo(Product::class);
    }
}
