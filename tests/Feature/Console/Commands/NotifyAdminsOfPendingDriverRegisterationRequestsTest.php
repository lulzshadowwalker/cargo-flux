<?php

namespace Tests\Unit\Console\Commands;

use App\Models\Driver;
use App\Models\User;
use App\Notifications\AdminPendingDriverRegisterationRequestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyAdminsOfPendingDriverRegisterationRequestsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_notifies_admins_of_pending_driver_registeration_requests_that_have_been_registered_for_longer_than_six_hours()
    {
        //
        Notification::fake();

        $admins = User::factory()->admin()->count(3)->create();

        Driver::WithoutEvents(function () {
            Driver::factory()->underReview()->create([
                'created_at' => now()->subHours(6)
            ]);

            Driver::factory()->underReview()->create([
                'created_at' => now()
            ]);
        });

        // 
        $this->artisan('notify:pending-driver-registeration-requests')
            ->assertSuccessful();

        //  NOTE: One driver has registered for six hours, 3 admins = 3 notifications
        Notification::assertCount(3);
        Notification::assertSentTo(
            $admins,
            AdminPendingDriverRegisterationRequestNotification::class,
        );
    }
}
