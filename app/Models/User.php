<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, \App\Concerns\BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    const ROLE_SUPER_ADMIN = 'SUPER_ADMIN';
    const ROLE_BRANCH_MANAGER = 'BRANCH_MANAGER';
    const ROLE_CASHIER = 'CASHIER';
    const ROLE_AUDITOR = 'AUDITOR';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'tenant_id',
        'branch_id',
        'device_uuid',
        'telegram_id',
        'is_active',
    ];

    public function isAdmin()
    {
        return in_array($this->role, ['SUPER_ADMIN', 'admin-pusat', 'owner']);
    }

    public function isBranchHead()
    {
        return in_array($this->role, ['kepala-cabang', 'wakil-kepala-cabang', 'SUPER_ADMIN']);
    }

    public function isCashier()
    {
        return $this->role === 'kasir';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the branch the user is assigned to.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permissionName)
    {
        return $this->role?->permissions()->where('name', $permissionName)->exists();
    }

    public function attendances() { return $this->hasMany(Attendance::class); }
    public function sopLogs() { return $this->hasMany(SopLog::class); }
    public function complaints() { return $this->hasMany(Complaint::class); }
    public function stockRequests() { return $this->hasMany(StockRequest::class); }

}
