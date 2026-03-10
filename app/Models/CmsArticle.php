<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsArticle extends Model
{
    use \App\Concerns\BelongsToTenant;
    protected $table = 'cms_articles';
    protected $fillable = [
        'tenant_id', 'title', 'slug', 'body', 'excerpt', 'featured_image',
        'status', 'meta_title', 'meta_description', 'category',
        'author_id', 'published_at',
    ];
    protected $casts = ['published_at' => 'datetime'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title) . '-' . Str::random(4);
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
