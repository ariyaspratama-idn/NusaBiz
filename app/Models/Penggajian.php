<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\BelongsToTenant;
use App\Concerns\Auditable;

class Penggajian extends Model
{
    use BelongsToTenant, Auditable;

    protected $fillable = [
        'tenant_id', 'karyawan_id', 'periode_bulan', 'gaji_pokok', 
        'tunjangan', 'potongan', 'lembur', 'total_gaji', 
        'status_pembayaran', 'tanggal_dibayar', 'slip_path'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
