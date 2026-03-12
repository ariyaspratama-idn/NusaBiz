<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\BelongsToTenant;
use App\Concerns\Auditable;

class Izin extends Model
{
    use BelongsToTenant, Auditable;

    protected $fillable = [
        'tenant_id', 'karyawan_id', 'tipe', 'tanggal_mulai', 
        'tanggal_selesai', 'alasan', 'bukti_path', 
        'status', 'approved_by', 'catatan_admin'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
