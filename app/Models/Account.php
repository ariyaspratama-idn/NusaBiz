<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Account extends Model
{
    use Auditable;
    protected $fillable = [
        'code', 'name', 'category', 'type', 'parent_id', 
        'normal_balance', 'description', 'is_header', 'is_active',
        'restricted_branch_id', 'monthly_budget', 'pic_id', 'attachment_path', 'default_tax_rate'
    ];

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function restrictedBranch()
    {
        return $this->belongsTo(Branch::class, 'restricted_branch_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function balances()
    {
        return $this->hasMany(AccountBalance::class);
    }
}
