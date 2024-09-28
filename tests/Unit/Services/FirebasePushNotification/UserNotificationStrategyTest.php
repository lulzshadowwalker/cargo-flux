<?php

namespace Tests\Unit\Services\FirebasePushNotification;

use App\Models\User;
use App\Services\FirebasePushNotification\UserNotificationStrategy;
use App\Support\PushNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserNotificationStrategyTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_matches_the_specification_correctly()
    {
        $this->assertFalse(UserNotificationStrategy::isSatisfiedBy('clearly-not-a-user'));
        $this->assertTrue(UserNotificationStrategy::isSatisfiedBy(User::factory()->create()), 'it is satisfied by a single user object');
        $this->assertTrue(UserNotificationStrategy::isSatisfiedBy([User::factory()->create()]), 'it is satisfied by an array of user objects');
    }

    public function test_it_sends_notification_successfully()
    {
        Http::fake([
            '*' => Http::response(['success' => true], 200),
        ]);

        $user = User::factory()->create();
        $user->deviceTokens()->create(['token' => 'fake-token']);

        $notification = new PushNotification('Test Title', 'Test Body', 'Test Image');

        UserNotificationStrategy::send($notification, $user);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                $request->url() === (string) UserNotificationStrategy::endpoint('messages:send') &&
                $request['message']['notification']['title'] === 'Test Title' &&
                $request['message']['notification']['body'] === 'Test Body' &&
                $request['message']['notification']['image'] === 'Test Image' &&
                $request['message']['token'] === 'fake-token';
        });
    }

    public function test_it_logs_error_when_notification_fails()
    {
        Http::fake([
            '*' => Http::response(['error' => 'Unauthorized'], 401),
        ]);

        Log::shouldReceive('error')
            ->once()
            ->with('Failed to send notification', \Mockery::on(function ($data) {
                return $data['status'] === 401 && $data['response'] === json_encode(['error' => 'Unauthorized']);
            }));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to send notification');

        $user = User::factory()->create();
        $user->deviceTokens()->create(['token' => 'fake-token']);

        $notification = new PushNotification('Test Title', 'Test Body', 'Test Image');

        UserNotificationStrategy::send($notification, $user);
    }
}
