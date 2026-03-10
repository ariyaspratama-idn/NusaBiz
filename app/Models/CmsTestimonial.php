<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsTestimonial extends Model
{
    use \App\Concerns\BelongsToTenant;
    protected $table = 'cms_testimonials';
    protected $fillable = ['tenant_id', 'customer_name', 'customer_position', 'customer_avatar', 'content', 'rating', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
