<?php

namespace Tests\Unit\Services\FirebasePushNotification;

use App\Enums\Audience;
use App\Models\User;
use App\Services\FirebasePushNotification\AudienceNotificationStrategy;
use App\Support\PushNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AudienceNotificationStrategyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_matches_the_specification_correctly()
    {
        $this->assertFalse(AudienceNotificationStrategy::isSatisfiedBy(User::factory()->create()));
        $this->assertTrue(AudienceNotificationStrategy::isSatisfiedBy(Audience::CUSTOMERS), 'it is satisfied by a single audience enum');
        $this->assertTrue(AudienceNotificationStrategy::isSatisfiedBy([Audience::CUSTOMERS, Audience::DRIVERS]), 'it is satisfied by an array of audience enums');
    }

    public function test_it_sends_notification_successfully()
    {
        Http::fake([
            '*' => Http::response(['message' => 'success'], 200),
        ]);

        $notification = new PushNotification('Test Title', 'Test Body', 'Test Image');
        $audience = Audience::CUSTOMERS;

        AudienceNotificationStrategy::send($notification, $audience);

        Http::assertSent(function ($request) use ($notification, $audience) {
            return $request->hasHeader('Authorization') &&
                $request->url() === (string) AudienceNotificationStrategy::endpoint('messages:send') &&
                $request['message']['notification']['title'] === $notification->title &&
                $request['message']['notification']['body'] === $notification->body &&
                $request['message']['notification']['image'] === $notification->image &&
                $request['message']['topic'] === $audience->value;
        });
    }

    public function test_it_logs_error_when_notification_fails()
    {
        Http::fake([
            '*' => Http::response(['message' => 'error'], 500),
        ]);

        Log::shouldReceive('error')->once();

        $this->expectException(\Exception::class);

        $notification = new PushNotification('Test Title', 'Test Body', 'Test Image');
        $audience = Audience::CUSTOMERS;

        AudienceNotificationStrategy::send($notification, $audience);
    }
}
