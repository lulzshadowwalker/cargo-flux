<?php

namespace App\Filament\Resources\TruckResource\Pages;

use App\Filament\Resources\TruckResource;
use App\Models\Truck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAdmin;

class ListTrucksTest extends TestCase
{
    use RefreshDatabase, WithAdmin;

    public function test_it_renders_the_page()
    {
        $this->get(TruckResource::getUrl('index'))->assertOk();
    }

    public function test_page_contains_truck_records()
    {
        $items = Truck::factory()->count(5)->create();

        Livewire::test(ListTrucks::class)
            ->assertCanSeeTableRecords($items);
    }

    public function test_records_have_a_view_action()
    {
        $this->markTestSkipped();

        Truck::factory()->create();

        Livewire::test(ListTrucks::class)
            ->assertSeeText('View');
    }
}
