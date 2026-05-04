<?php

namespace Tests\Feature;

use App\Enums\NotificationStatus;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StatusChangeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_status_change(): void
    {
        $user = User::factory()->createOne();
        $notification = Notification::create([
            'text' => 'text',
            'channel' => 'email',
            'user_id' => $user->id,
        ]);

        $notification->status = NotificationStatus::Error->value;
        $notification->save();

        // Check if any observers events may have possibly switched it to a different value
        $this->assertTrue($notification->status === NotificationStatus::Error->value);
    }
}
