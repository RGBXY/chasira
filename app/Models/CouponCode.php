<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    protected $fillable = [
        'code',
        'discount_id',
    ];

    public function Discount_transaction(){
        return $this->belongsTo(DiscountTransaction::class);
    }
}
