<?php

namespace Tests\Feature\Filament\Resources\RouteGroupResource\Pages;

use App\Filament\Resources\RouteGroupResource;
use App\Filament\Resources\RouteGroupResource\Pages\CreateRouteGroup;
use App\Models\Currency;
use App\Models\RouteGroup;
use App\Models\TruckCategory;
use Database\Factories\StateFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAdmin;

class CreateRouteGroupTest extends TestCase
{
    use RefreshDatabase, WithAdmin;

    public function test_it_renders_the_page(): void
    {
        $this->get(RouteGroupResource::getUrl('create'))
            ->assertOk();
    }

    public function test_it_creates_a_route(): void
    {
        $states = StateFactory::new()->count(5)->create();
        $truckCategories = TruckCategory::factory()->count(5)->create();
        $currency = Currency::factory()->create();

        Livewire::test(CreateRouteGroup::class)
            ->fillForm([
                'pickup_state_id' => $states[0]->id,
                'truck_options' => [
                    [
                        'truck_category_id' => $truckCategories[0]->id,
                        'amount' => 39.40,
                        'currency_id' => $currency->id,
                    ],
                ],
                'destinations' => [
                    $states[1]->id,
                    $states[2]->id,
                    $states[3]->id,
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('route_groups', [
            'pickup_state_id' => $states[0]->id,
        ]);

        $this->assertDatabaseHas('route_group_truck_options', [
            'route_group_id' => RouteGroup::first()->id,
            'truck_category_id' => $truckCategories[0]->id,
            'amount' => 39.40,
            'currency_id' => $currency->id,
        ]);

        $this->assertDatabaseCount('route_group_destinations', 3);
        $this->assertDatabaseHas('route_group_destinations', [
            'route_group_id' => RouteGroup::first()->id,
            'delivery_state_id' => $states[1]->id,
        ]);
    }
}
