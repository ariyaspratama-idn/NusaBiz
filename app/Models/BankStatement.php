<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class BankStatement extends Model
{
    use Auditable;

    protected $fillable = [
        'date', 'description', 'amount', 'reference', 'transaction_id', 'status'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
