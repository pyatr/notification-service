<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::post('/send-notification', [NotificationController::class, 'sendNotification']);
Route::get('/notification-status/{notification}', [NotificationController::class, 'getNotificationStatus']);
Route::get('/user-notifications/{user}', [NotificationController::class, 'getUserNotifications']);
