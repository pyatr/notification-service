<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendNotificationRequest;
use App\Http\Requests\ViewUserNotifications;
use App\Jobs\SendNotification;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;

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

    public function getUserNotifications(User $user, ViewUserNotifications $viewUserNotifications): JsonResponse
    {
        $query = $user->notifications()
            ->select(['id', 'text', 'channel', 'status', 'created_at']);
        $channel = $viewUserNotifications->input('channel');
        $status = $viewUserNotifications->input('status');

        if (isset($channel)) {
            $query->where('channel', $channel);
        }

        if (isset($status)) {
            $query->where('status', $status);
        }

        $notifications = $query->get();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }
}
