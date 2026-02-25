<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatGuest extends Model
{
    protected $fillable = [
        'username',
        'email',
        'phone',
        'session_token',
        'ip_address',
        'user_agent',
    ];

    public function conversations(): HasMany
    {
        return $this->hasMany(ChatConversation::class, 'guest_id');
    }
}

