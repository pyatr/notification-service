<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NotificationCreationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_notification_creation(): void
    {
        $user = User::factory()->createOne();
        $response = $this->post(
            '/api/send-notification',
            [
                'channel' => 'email',
                'text' => 'test',
                'user_id' => $user->id,
            ]
        );

        $response->assertStatus(200);
    }
}
