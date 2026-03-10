<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use \App\Concerns\BelongsToTenant;
    protected $table = 'product_categories';
    protected $fillable = ['tenant_id', 'name', 'slug', 'icon', 'image', 'description', 'is_active'];

    public function products()
    {
        return $this->hasMany(EcProduct::class, 'category_id');
    }
}
