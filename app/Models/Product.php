<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \App\Concerns\BelongsToTenant, \App\Concerns\Auditable;

    protected $fillable = [
        'tenant_id', 'code', 'name', 'category_id', 'unit', 'purchase_price', 
        'selling_price', 'account_id_inventory', 'account_id_cogs', 
        'account_id_sales', 'min_stock', 'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockBalances()
    {
        return $this->hasMany(StockBalance::class);
    }

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class, 'product_id');
    }

    public function materials()
    {
        return $this->belongsToMany(Product::class, 'product_recipes', 'product_id', 'material_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
