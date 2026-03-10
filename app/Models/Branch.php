<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use \App\Concerns\BelongsToTenant, \App\Concerns\Auditable;

    protected $fillable = ['tenant_id', 'code', 'name', 'type', 'address', 'phone', 'is_active', 'google_maps_id'];

    public function accountBalances()
    {
        return $this->hasMany(AccountBalance::class);
    }

    public function journalHeaders()
    {
        return $this->hasMany(JournalHeader::class);
    }

    public function stockBalances()
    {
        return $this->hasMany(StockBalance::class);
    }

    public function attendances() { return $this->hasMany(Attendance::class); }
    public function sopLogs() { return $this->hasMany(SopLog::class); }
    public function sops() { return $this->belongsToMany(Sop::class, 'branch_sop'); }
    public function complaints() { return $this->hasMany(Complaint::class); }
    public function stockRequests() { return $this->hasMany(StockRequest::class); }
}
