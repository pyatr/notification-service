<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendNotificationRequest;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendTelegramNotification;
use App\Models\Notification;
use App\Enums\NotificationChannel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class NotificationController extends Controller
{
    public function sendNotification(SendNotificationRequest $sendNotificationRequest): JsonResponse
    {
        $channel = $sendNotificationRequest->input('channel');

        $notification = Notification::create([
            'text' => $sendNotificationRequest->input('text'),
            'channel' => $channel,
            'user_id' => $sendNotificationRequest->input('user_id'),
        ]);

        switch ($channel) {
            case NotificationChannel::EMail->value:
                SendEmailNotification::dispatch($notification);
                break;
            case NotificationChannel::Telegram->value:
                SendTelegramNotification::dispatch($notification);
                break;
            default:
                throw new InvalidArgumentException("Unsupported notification channel: $channel");
        }

        return response()->json([
            'notification_id' => $notification->id,
        ]);
    }

    public function getNotificationStatus(Notification $notification): JsonResponse
    {
        return response()->json([
            'notification_status' => $notification->status,
        ]);
    }
}
