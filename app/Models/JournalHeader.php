<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalHeader extends Model
{
    protected $fillable = [
        'journal_no', 'journal_date', 'branch_id', 'description', 
        'total_debit', 'total_credit', 'status', 'reference_type', 
        'reference_id', 'created_by', 'posted_by', 'posted_at',
        'evidence_path', 'branch_head_approved_at', 'branch_head_approved_by', 'rejection_reason'
    ];

    protected $casts = [
        'journal_date' => 'date',
        'posted_at' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
