<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountTransaction extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'discount',
        'status',
        'minimal_transaction',
        'customer_only',
        'description',
    ];

    public function CouponCode(){
        return $this->hasMany(CouponCode::class);
    }
}
