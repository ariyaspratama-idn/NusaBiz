<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Transaction extends Model
{
    use \App\Concerns\BelongsToTenant, \App\Concerns\Auditable;
    protected $fillable = [
        'tenant_id', 'transaction_no', 'transaction_date', 'branch_id', 'type', 
        'contact_id', 'account_id', 'amount', 'description', 
        'journal_header_id', 'created_by', 'payment_status', 'cash_register_id',
        'evidence_path', 'branch_head_approved_at', 'branch_head_approved_by', 'rejection_reason'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function journal()
    {
        return $this->belongsTo(JournalHeader::class, 'journal_header_id');
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public static function generateUniqueNo()
    {
        $prefix = 'TRX-' . date('Ymd') . '-';
        do {
            $code = $prefix . strtoupper(bin2hex(random_bytes(3))); // e.g. TRX-20260215-A1B2C3
        } while (self::where('transaction_no', $code)->exists());
        
        return $code;
    }
}
