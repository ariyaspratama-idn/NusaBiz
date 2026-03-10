<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use \App\Concerns\BelongsToTenant;
    protected $table = 'chat_sessions';
    protected $fillable = ['tenant_id', 'session_key', 'visitor_name', 'visitor_email', 'visitor_phone', 'status', 'last_activity_at'];
    protected $casts = ['last_activity_at' => 'datetime'];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'session_id');
    }

    public function unreadCount(): int
    {
        return $this->messages()->where('sender_type', 'visitor')->where('is_read', false)->count();
    }
}
