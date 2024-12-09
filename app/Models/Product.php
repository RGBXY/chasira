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
        'user_id',
        'family_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
