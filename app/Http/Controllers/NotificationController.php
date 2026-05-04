<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendNotificationRequest;
use App\Jobs\SendNotification;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        SendNotification::dispatch($notification);

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
