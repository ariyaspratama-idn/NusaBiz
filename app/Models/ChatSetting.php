<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSetting extends Model
{
    use \App\Concerns\BelongsToTenant;
    protected $table = 'chat_settings';
    protected $fillable = ['tenant_id', 'is_online', 'operating_hours', 'offline_message', 'welcome_message'];
    protected $casts = [
        'is_online'       => 'boolean',
        'operating_hours' => 'array',
    ];
}
