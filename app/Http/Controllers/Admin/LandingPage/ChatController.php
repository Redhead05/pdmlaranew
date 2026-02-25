<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Events\ChatMessageSent;
use App\Events\ChatMessagesRead;
use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('menu.adminlanding.chat.index');
    }

    public function conversations(Request $request): JsonResponse
    {
        $conversations = ChatConversation::query()
            ->with(['guest:id,username,email,phone'])
            ->withCount([
                // unread for admin = guest messages that haven't been read
                'messages as unread_count' => function ($q) {
                    $q->where('sender_type', 'guest')->whereNull('read_at');
                },
            ])
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return response()->json([
            'conversations' => $conversations,
        ]);
    }

    public function messages(Request $request, ChatConversation $conversation): JsonResponse
    {
        $conversation->loadMissing('guest:id,username,email,phone');

        $messages = $conversation->messages()
            ->orderBy('id')
            ->limit(300)
            ->get(['id', 'sender_type', 'sender_id', 'body', 'sent_at', 'read_at']);

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'status' => $conversation->status,
                'guest' => $conversation->guest,
            ],
            'messages' => $messages,
        ]);
    }

    public function reply(Request $request, ChatConversation $conversation): JsonResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:2000'],
        ]);

        if ($conversation->status !== 'open') {
            return response()->json(['message' => 'Percakapan sudah ditutup.'], 409);
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'admin',
            'sender_id' => $request->user()->id,
            'body' => trim($data['body']),
            'sent_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        $conversation->update(['last_message_at' => now()]);

        broadcast(new ChatMessageSent($message))->toOthers();

        return response()->json(['message' => $message]);
    }

    public function markRead(Request $request, ChatConversation $conversation): JsonResponse
    {
        // mark all guest messages as read by admin (single read_at for simplicity)
        $now = now();

        $updated = $conversation->messages()
            ->where('sender_type', 'guest')
            ->whereNull('read_at')
            ->update(['read_at' => $now]);

        if ($updated > 0) {
            broadcast(new ChatMessagesRead(
                conversationId: $conversation->id,
                readerType: 'admin',
                adminId: $request->user()->id,
                guestToken: null,
                readAtIso: $now->toIso8601String(),
            ))->toOthers();
        }

        return response()->json(['updated' => $updated]);
    }
}
