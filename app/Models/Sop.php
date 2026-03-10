<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sop extends Model
{
    protected $fillable = ['name', 'category', 'description', 'is_active'];

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_sop');
    }
}
