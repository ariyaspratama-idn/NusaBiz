<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\BelongsToTenant;
use App\Concerns\Auditable;

class Absensi extends Model
{
    use BelongsToTenant, Auditable;

    protected $fillable = [
        'tenant_id', 'karyawan_id', 'branch_id', 'tanggal', 
        'jam_masuk', 'jam_pulang', 'lat_masuk', 'lon_masuk', 
        'foto_masuk', 'lat_pulang', 'lon_pulang', 'foto_pulang', 
        'status', 'menit_lembur', 'is_approved', 'catatan'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
