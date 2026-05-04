<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendTelegramNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Notification $notification)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Telegram::sendMessage([
            'chat_id' => $this->notification->user->chat_id,
            'text' => $this->notification->text,
        ]);
    }
}
