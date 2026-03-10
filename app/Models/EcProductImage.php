<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcProductImage extends Model
{
    use \App\Concerns\BelongsToTenant;
    protected $table = 'ec_product_images';
    protected $fillable = ['tenant_id', 'product_id', 'image_path', 'sort_order'];
}
