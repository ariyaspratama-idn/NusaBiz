<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcProductVariant extends Model
{
    use \App\Concerns\BelongsToTenant;
    protected $table = 'ec_product_variants';
    protected $fillable = ['tenant_id', 'product_id', 'variant_name', 'variant_value', 'price_modifier', 'stock', 'sku'];
}
