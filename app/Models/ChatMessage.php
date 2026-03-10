<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $table = 'chat_messages';
    protected $fillable = ['session_id', 'sender_type', 'admin_id', 'message', 'attachment', 'is_read'];
    protected $casts = ['is_read' => 'boolean'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
