<?php

namespace App\Http\Requests;

use App\Enums\NotificationChannel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendNotificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => [
                'required',
                'max:500',
                'min:1',
            ],
            'user_id' => [
                'required',
                'exists:users,id'
            ],
            'channel' => [
                'required',
                'in' => Rule::in([NotificationChannel::EMail->value, NotificationChannel::Telegram->value]),
            ],
        ];
    }
}
