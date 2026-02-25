<?php

namespace App\Http\Controllers\Chat;

use App\Events\ChatMessageSent;
use App\Models\ChatConversation;
use App\Models\ChatGuest;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class GuestChatController
{
    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $ip = $request->ip();

        // Block new starts from IP temporarily if spam detected
        $blockMinutes = (int) config('chat.anti_spam.block_start_minutes', 10);
        $blockKey = 'chat:block-start:' . $ip;
        if (RateLimiter::tooManyAttempts($blockKey, 1)) {
            return response()->json(['message' => 'Terlalu banyak aktivitas chat dari IP ini. Coba lagi beberapa menit lagi.'], 429);
        }

        $rateKey = 'chat:start:' . $ip;
        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            return response()->json(['message' => 'Terlalu banyak percobaan, coba lagi sebentar.'], 429);
        }
        RateLimiter::hit($rateKey, 60);

        $token = $request->cookie('chat_guest_token');

        $guest = null;
        if ($token) {
            $guest = ChatGuest::where('session_token', $token)->first();
        }

        if (!$guest) {
            $token = Str::random(64);
            $guest = ChatGuest::create([
                'username' => trim($data['username']),
                'email' => strtolower(trim($data['email'])),
                'phone' => preg_replace('/\s+/', '', $data['phone']),
                'session_token' => $token,
                'ip_address' => $ip,
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            ]);
        } else {
            // update latest profile quietly if changed
            $guest->update([
                'username' => trim($data['username']),
                'email' => strtolower(trim($data['email'])),
                'phone' => preg_replace('/\s+/', '', $data['phone']),
                'ip_address' => $ip,
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            ]);
        }

        $conversation = ChatConversation::where('guest_id', $guest->id)->where('status', 'open')->latest('id')->first();
        if (!$conversation) {
            $conversation = ChatConversation::create([
                'guest_id' => $guest->id,
                'status' => 'open',
                'last_message_at' => now(),
            ]);
        } else {
            // ensure it stays on top in admin inbox after guest re-opens widget
            if (!$conversation->last_message_at) {
                $conversation->update(['last_message_at' => now()]);
            }
        }

        return response()
            ->json([
                'guest' => [
                    'username' => $guest->username,
                    'email' => $guest->email,
                    'phone' => $guest->phone,
                ],
                'conversation' => [
                    'id' => $conversation->id,
                    'status' => $conversation->status,
                ],
                'token' => $token,
            ])
            ->cookie('chat_guest_token', $token, 60 * 24 * 30); // 30 days
    }

    public function messages(Request $request, ChatConversation $conversation): JsonResponse
    {
        $guest = $this->resolveGuestOrFail($request, $conversation);

        $messages = $conversation->messages()->orderBy('id')->limit(200)->get([
            'id', 'sender_type', 'sender_id', 'body', 'sent_at', 'read_at',
        ]);

        return response()->json([
            'guest' => [
                'username' => $guest->username,
                'email' => $guest->email,
                'phone' => $guest->phone,
            ],
            'conversation' => [
                'id' => $conversation->id,
                'status' => $conversation->status,
            ],
            'messages' => $messages,
        ]);
    }

    public function send(Request $request, ChatConversation $conversation): JsonResponse
    {
        $guest = $this->resolveGuestOrFail($request, $conversation);

        if ($conversation->status !== 'open') {
            return response()->json(['message' => 'Percakapan sudah ditutup.'], 409);
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:2000'],
        ]);

        $body = trim($data['body']);
        $ip = $request->ip();

        $blockMinutes = (int) config('chat.anti_spam.block_start_minutes', 10);
        $dailyCap = (int) config('chat.anti_spam.daily_cap', 300);

        // 1) Hard rate limit per conversation + ip
        $rateKey = 'chat:send:' . $conversation->id . ':' . $ip;
        if (RateLimiter::tooManyAttempts($rateKey, 15)) {
            return response()->json(['message' => 'Terlalu banyak pesan, coba lagi sebentar.'], 429);
        }
        RateLimiter::hit($rateKey, 60);

        // 2) Rate limit per guest token (covers shared IP, proxies)
        $token = (string) $request->cookie('chat_guest_token');
        $rateKeyToken = 'chat:send-token:' . $conversation->id . ':' . substr($token, 0, 20);
        if (RateLimiter::tooManyAttempts($rateKeyToken, 25)) {
            return response()->json(['message' => 'Terlalu banyak pesan dari sesi ini, coba lagi sebentar.'], 429);
        }
        RateLimiter::hit($rateKeyToken, 60);

        // 3) Block duplicate consecutive messages (same body)
        $last = $conversation->messages()->latest('id')->first(['id', 'sender_type', 'body', 'sent_at']);
        if ($last && $last->sender_type === 'guest' && trim((string) $last->body) === $body) {
            return response()->json(['message' => 'Pesan yang sama sudah terkirim. Mohon tunggu.'], 429);
        }

        // 4) Daily cap per conversation (prevents DB floods)
        $todayCount = $conversation->messages()
            ->where('sender_type', 'guest')
            ->where('sent_at', '>=', now()->startOfDay())
            ->count();

        if ($todayCount >= $dailyCap) {
            // Auto-close conversation and block new starts from this IP for X minutes
            $conversation->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            RateLimiter::hit('chat:block-start:' . $ip, $blockMinutes * 60);

            return response()->json(['message' => 'Batas pesan hari ini tercapai. Silakan lakukan chat esok hari.'], 429);
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'guest',
            'sender_id' => null,
            'body' => $body,
            'sent_at' => now(),
            'ip_address' => $ip,
        ]);

        $conversation->update(['last_message_at' => now()]);

        broadcast(new ChatMessageSent($message))->toOthers();

        return response()->json(['message' => $message]);
    }

    private function resolveGuestOrFail(Request $request, ChatConversation $conversation): ChatGuest
    {
        $token = $request->cookie('chat_guest_token');
        if (!$token) {
            abort(401);
        }

        $guest = ChatGuest::where('session_token', $token)->first();
        if (!$guest || $conversation->guest_id !== $guest->id) {
            abort(403);
        }

        return $guest;
    }
}

