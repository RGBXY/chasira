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
        'code',
        'minimal_transaction',
        'customer_only',
        'description',
    ];
} 
