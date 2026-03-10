<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    protected $fillable = ['journal_header_id', 'account_id', 'debit', 'credit', 'description'];

    public function header()
    {
        return $this->belongsTo(JournalHeader::class, 'journal_header_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
