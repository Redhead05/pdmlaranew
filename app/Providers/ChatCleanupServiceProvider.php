<?php

namespace App\Providers;

use App\Console\Commands\ChatCleanupCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class ChatCleanupServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register command for artisan
        $this->commands([
            ChatCleanupCommand::class,
        ]);
    }

    public function boot(Schedule $schedule): void
    {
        $days = (int) config('chat.cleanup.days', 14);

        // Run daily at 01:30 server time
        $schedule->command("chat:cleanup --days={$days}")
            ->dailyAt('01:30')
            ->withoutOverlapping()
            ->onOneServer();
    }
}
