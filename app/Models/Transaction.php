<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'invoice',
        'cash',
        'change',
        'discount',
        'grand_total',
    ];

    public function details(){
        return $this->hasMany(TransactionDetail::class);
    }

    public function cashier(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profits()
    {
        return $this->hasMany(Profit::class);
    }
}
