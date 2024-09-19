<?php

namespace Tests\Feature\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAdmin;

class ListOrdersTest extends TestCase
{
    use RefreshDatabase, WithAdmin;

    public function test_it_renders_the_page()
    {
        $this->get(OrderResource::getUrl('index'))->assertOk();
    }

    public function test_page_contains_order_records()
    {
        $items = Order::factory()->count(5)->create();

        Livewire::test(ListOrders::class)
            ->assertCanSeeTableRecords($items);
    }

    public function test_records_have_a_view_action()
    {
        $this->markTestSkipped();

        Order::factory()->create();

        Livewire::test(ListOrders::class)
            ->assertSeeText('View');
    }
}
