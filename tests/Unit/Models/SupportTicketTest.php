<?php

namespace Tests\Unit\Models;

use App\Models\SupportTicket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_assigns_a_ticket_number_on_creation(): void
    {
        $ticket = SupportTicket::factory()->create();
        $this->assertStringStartsWith('TICKET-', $ticket->number);
    }

    public function test_it_starts_with_open_status_if_status_is_not_specified(): void
    {
        $ticket = SupportTicket::create(['subject' => 'Test subject', 'message' => 'Test message', 'phone' => '+1234567890', 'name' => 'John Doe',]);

        $this->assertTrue($ticket->isOpen);
        $this->assertFalse($ticket->isInProgress);
        $this->assertFalse($ticket->isResolved);
    }

    public function test_status_can_be_specified(): void
    {
        $ticket = SupportTicket::create(['subject' => 'Test subject', 'message' => 'Test message', 'phone' => '+1234567890', 'name' => 'John Doe', 'status' => 'IN_PROGRESS']);

        $this->assertFalse($ticket->isOpen);
        $this->assertTrue($ticket->isInProgress);
        $this->assertFalse($ticket->isResolved);
    }
}
