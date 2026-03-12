<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashierSession extends Model
{
    protected $fillable = [
        'user_id', 
        'branch_id', 
        'opening_balance', 
        'closing_balance', 
        'opened_at', 
        'closed_at', 
        'status', 
        'evidence_path', 
        'description', 
        'approved_by', 
        'approved_at',
        'shift',
        'supervisor_id',
        'supervisor_nip'
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
