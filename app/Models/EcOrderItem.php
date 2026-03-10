<?php

namespace App\Models;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class EcOrderItem extends Model
{
    use BelongsToTenant;

    protected $table = 'ec_order_items';
    protected $fillable = [
        'tenant_id', 'order_id', 'product_id', 'variant_id',
        'product_name', 'variant_info', 'price', 'quantity', 'subtotal',
    ];
}
