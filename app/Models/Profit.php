<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $fillable = [
        'transaction_id',
        'total',
    ];

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }
}
