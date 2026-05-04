<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserNotificationListTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_notification_list(): void
    {
        $user = User::factory()->createOne();
        Notification::create([
            'text' => 'text',
            'channel' => 'email',
            'user_id' => $user->id,
        ]);
        $response = $this->get("/api/user-notifications/{$user->id}?channel=email");

        $response->assertStatus(200);
    }
}
