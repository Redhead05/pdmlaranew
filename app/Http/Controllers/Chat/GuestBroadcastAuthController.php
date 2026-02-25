<?php

namespace App\Http\Controllers\Chat;

use App\Models\ChatConversation;
use App\Models\ChatGuest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pusher\Pusher;

class GuestBroadcastAuthController
{
    /**
     * Auth endpoint for Laravel Echo private channels for guests (no login).
     * Echo will POST: { socket_id, channel_name }
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'socket_id' => ['required', 'string'],
            'channel_name' => ['required', 'string'],
        ]);

        $token = $request->cookie('chat_guest_token');
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $guest = ChatGuest::where('session_token', $token)->first();
        if (!$guest) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Only allow subscribing to: private-chat.conversation.{id}
        if (!preg_match('/^private-chat\.conversation\.(\d+)$/', $data['channel_name'], $m)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $conversationId = (int) $m[1];
        $conversation = ChatConversation::find($conversationId);
        if (!$conversation || $conversation->guest_id !== $guest->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $pusher = new Pusher(
            app('config')->get('broadcasting.connections.reverb.key'),
            app('config')->get('broadcasting.connections.reverb.secret'),
            app('config')->get('broadcasting.connections.reverb.app_id'),
            app('config')->get('broadcasting.connections.reverb.options')
        );

        $auth = $pusher->socket_auth($data['channel_name'], $data['socket_id']);

        return response()->json(json_decode($auth, true));
    }
}

