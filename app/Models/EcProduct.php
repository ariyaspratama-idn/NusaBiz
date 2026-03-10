<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EcProduct extends Model
{
    use SoftDeletes, \App\Concerns\BelongsToTenant;

    protected $table = 'ec_products';

    protected $fillable = [
        'tenant_id', 'category_id', 'name', 'slug', 'sku', 'description',
        'main_image', 'price', 'sale_price', 'stock',
        'min_stock_alert', 'weight', 'status', 'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name) . '-' . Str::random(5);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(EcProductImage::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(EcProductVariant::class, 'product_id');
    }

    public function getEffectivePriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock_alert;
    }
}
