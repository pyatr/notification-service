<?php

namespace App\Enums;

enum NotificationChannel: string
{
    case EMail = 'email';
    case Telegram = 'telegram';
}
