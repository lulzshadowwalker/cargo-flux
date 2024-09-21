<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages\ListSupportTickets;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAdmin;

class ListSupportTicketsTest extends TestCase
{
    use RefreshDatabase, WithAdmin;

    public function test_it_renders_the_page()
    {
        $this->get(SupportTicketResource::getUrl('index'))->assertOk();
    }

    public function test_page_contains_support_ticket_records()
    {
        $items = SupportTicket::factory()->count(5)->create();

        Livewire::test(ListSupportTickets::class)
            ->assertCanSeeTableRecords($items);
    }

    public function test_records_have_an_edit_action()
    {
        SupportTicket::factory()->create();

        Livewire::test(ListSupportTickets::class)
            ->assertSeeText('Edit');
    }
}
