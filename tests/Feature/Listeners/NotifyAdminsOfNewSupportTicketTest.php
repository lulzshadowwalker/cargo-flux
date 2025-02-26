<?php

namespace Tests\Feature\Listeners;

use App\Events\SupportTicketReceived;
use App\Listeners\NotifyAdminsOfNewSupportTicket;
use App\Models\SupportTicket;
use App\Models\User;
use App\Notifications\AdminSupportTicketReceivedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyAdminsOfNewSupportTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_listens_to_support_ticket_received_event()
    {
        Event::fake();

        Event::assertListening(
            SupportTicketReceived::class,
            NotifyAdminsOfNewSupportTicket::class,
        );
    }

    public function test_it_sends_the_support_ticket_received_notification_to_admins()
    {
        //
        Notification::fake();

        $admins = User::factory()->count(3)->admin()->create();

        SupportTicket::withoutEvents(function () {
            SupportTicket::factory()->create(['number' => '123']);
        });

        //
        $listener = new NotifyAdminsOfNewSupportTicket();
        $listener->handle(new SupportTicketReceived(SupportTicket::first()));

        //
        Notification::assertSentTo(
            $admins,
            AdminSupportTicketReceivedNotification::class,
        );
    }
}
