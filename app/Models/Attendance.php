<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use \App\Concerns\BelongsToTenant, \App\Concerns\Auditable;

    protected $fillable = [
        'tenant_id', 'user_id', 'branch_id', 'date', 'clock_in', 'clock_out', 'status', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
