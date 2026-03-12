<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\BelongsToTenant;

class JadwalKerja extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'nama_jadwal', 'jam_masuk', 'jam_pulang', 
        'toleransi_keterlambatan', 'is_default'
    ];
}
