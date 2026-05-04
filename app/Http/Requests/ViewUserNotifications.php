<?php

namespace App\Http\Requests;

use App\Enums\NotificationChannel;
use App\Enums\NotificationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ViewUserNotifications extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'channel' => [
                'string',
                'in' => Rule::in([NotificationChannel::EMail->value, NotificationChannel::Telegram->value]),
            ],
            'status' => [
                'string',
                'in' => Rule::in([NotificationStatus::Pending->value, NotificationStatus::Sent->value, NotificationStatus::Error->value]),
            ],
        ];
    }
}
