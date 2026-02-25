<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessagesRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $conversationId,
        public string $readerType, // admin|guest
        public ?int $adminId = null,
        public ?string $guestToken = null,
        public ?string $readAtIso = null,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.conversation.' . $this->conversationId),
            new PrivateChannel('chat.admin.inbox'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat.messages.read';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'reader_type' => $this->readerType,
            'admin_id' => $this->adminId,
            'read_at' => $this->readAtIso,
        ];
    }
}

