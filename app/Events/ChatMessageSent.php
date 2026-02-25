<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
        $this->message->loadMissing('conversation.guest');
    }

    public function broadcastOn(): array
    {
        $conversationId = $this->message->conversation_id;

        return [
            new PrivateChannel('chat.conversation.' . $conversationId),
            new PrivateChannel('chat.admin.inbox'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat.message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_type' => $this->message->sender_type,
            'sender_id' => $this->message->sender_id,
            'body' => $this->message->body,
            'sent_at' => optional($this->message->sent_at)->toIso8601String(),
            'read_at' => optional($this->message->read_at)->toIso8601String(),
            'guest' => [
                'id' => $this->message->conversation->guest->id,
                'username' => $this->message->conversation->guest->username,
                'email' => $this->message->conversation->guest->email,
                'phone' => $this->message->conversation->guest->phone,
            ],
        ];
    }
}

