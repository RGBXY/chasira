<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'chasier_id',
        'invoice',
        'cash',
        'change',
        'discount',
        'grand_total',
    ];

    public function chasier(){
        return $this->belongsTo(User::class, 'chasier_id');
    }

    public function profits()
    {
        return $this->hasMany(Profit::class);
    }
}
