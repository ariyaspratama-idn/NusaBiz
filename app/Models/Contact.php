<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['type', 'code', 'name', 'email', 'phone', 'address'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
