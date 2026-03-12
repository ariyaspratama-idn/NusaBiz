<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\BelongsToTenant;
use App\Concerns\Auditable;

class Karyawan extends Model
{
    use SoftDeletes, BelongsToTenant, Auditable;

    protected $fillable = [
        'tenant_id', 'user_id', 'nip', 'nama_lengkap', 'no_hp', 
        'departemen', 'jabatan', 'gaji_pokok', 'tanggal_masuk', 
        'sisa_cuti', 'status', 'foto_profil', 'has_face_registered', 
        'telegram_chat_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function izins()
    {
        return $this->hasMany(Izin::class);
    }
}
