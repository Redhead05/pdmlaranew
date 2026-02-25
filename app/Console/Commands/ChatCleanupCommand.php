<?php

namespace App\Console\Commands;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class ChatCleanupCommand extends Command
{
    protected $signature = 'chat:cleanup {--days=14 : Delete chat data older than N days}';

    protected $description = 'Cleanup old chat messages and conversations to prevent database from growing indefinitely.';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        if ($days < 1) {
            $this->error('Days must be >= 1');
            return self::FAILURE;
        }

        $cutoff = CarbonImmutable::now()->subDays($days);

        // Delete messages older than cutoff
        $messagesDeleted = ChatMessage::query()
            ->where('sent_at', '<', $cutoff)
            ->delete();

        // Delete conversations that have no messages left (or last_message_at old)
        $conversationsDeleted = ChatConversation::query()
            ->where(function ($q) use ($cutoff) {
                $q->whereNull('last_message_at')->orWhere('last_message_at', '<', $cutoff);
            })
            ->whereDoesntHave('messages')
            ->delete();

        $this->info("Deleted {$messagesDeleted} messages older than {$days} days.");
        $this->info("Deleted {$conversationsDeleted} empty old conversations.");

        return self::SUCCESS;
    }
}

