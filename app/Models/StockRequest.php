<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    protected $fillable = [
        'user_id', 'branch_id', 'item_name', 'quantity', 'reason', 'purpose', 'status',
        'evidence_path', 'branch_head_approved_at', 'branch_head_approved_by', 'rejection_reason'
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
