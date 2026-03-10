<?php

namespace App\Models;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class EcOrderStatusHistory extends Model
{
    use BelongsToTenant;

    protected $table = 'ec_order_status_histories';
    protected $fillable = ['tenant_id', 'order_id', 'status', 'notes', 'updated_by'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
