<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'buy_price',
        'sell_price',
        'description',
        'stock',
        'image',
        'category_id',
        'barcode'       
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stock_out()
    {
        return $this->belongsTo(StockOut::class);
    }
}
