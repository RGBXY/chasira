<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'gender',
        'description',
    ];

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }
}
