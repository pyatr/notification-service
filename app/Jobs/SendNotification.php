<?php

namespace App\Jobs;

use App\Enums\NotificationChannel;
use App\Enums\NotificationStatus;
use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\Attributes\Backoff;
use Illuminate\Support\Facades\Mail;
use Telegram\Bot\Laravel\Facades\Telegram;

#[Backoff([1, 5, 10, 20, 30, 60])]
class SendNotification implements ShouldQueue
{
    public int $tries = 0;

    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Notification $notification) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            /**
             * @var User
             */
            $user = $this->notification->user;

            switch ($this->notification->channel) {
                case NotificationChannel::EMail->value:
                    Mail::to($user)->send((new Mailable)->view('mail', ['text' => $this->notification->text]));

                    break;
                case NotificationChannel::Telegram->value:

                    Telegram::sendMessage([
                        'chat_id' => $user->chat_id,
                        'text' => $this->notification->text,
                    ]);

                    break;
                default:
                    $this->delete();
                    throw new Exception('Unsupported notification channel');
            }

            $this->notification->update(['status' => NotificationStatus::Sent->value]);
        } catch (Exception $e) {
            $this->notification->update(['status' => NotificationStatus::Error->value]);
            throw $e;
        }
    }
}
