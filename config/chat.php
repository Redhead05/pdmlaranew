<?php

return [
    'anti_spam' => [
        // daily cap per conversation (guest -> admin)
        'daily_cap' => env('CHAT_DAILY_CAP', 300),

        // block starting a new chat from a spammy IP for N minutes
        'block_start_minutes' => env('CHAT_BLOCK_START_MINUTES', 10),
    ],

    'cleanup' => [
        // automatically delete chat messages older than N days
        'days' => env('CHAT_CLEANUP_DAYS', 14),
    ],
];

