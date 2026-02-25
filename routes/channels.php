<?php

use App\Models\ChatConversation;
use App\Models\ChatGuest;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.admin.inbox', function ($user) {
    return $user && method_exists($user, 'hasAnyRole')
        ? $user->hasAnyRole(['adminlanding', 'admin'])
        : false;
});

Broadcast::channel('chat.conversation.{conversationId}', function ($user = null, $conversationId = null) {
    // Admin users (authenticated) are allowed.
    if ($user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['adminlanding', 'admin'])) {
        return true;
    }

    // Guests are authenticated via a custom broadcasting auth endpoint that sets the guest token.
    // When using the default /broadcasting/auth, guests won't be authenticated.
    return false;
});

