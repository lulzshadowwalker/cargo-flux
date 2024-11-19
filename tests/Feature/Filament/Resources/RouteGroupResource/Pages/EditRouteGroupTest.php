<?php

namespace Tests\Feature\Filament\Resources\RouteGroupResource\Pages;

use App\Filament\Resources\RouteGroupResource;
use App\Filament\Resources\RouteGroupResource\Pages\EditRouteGroup;
use App\Models\Currency;
use App\Models\RouteGroup;
use App\Models\RouteGroupDestination;
use App\Models\RouteGroupTruckOption;
use Database\Factories\StateFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAdmin;

class EditRouteGroupTest extends TestCase
{
    use RefreshDatabase, WithAdmin;

    public RouteGroup $routeGroup;

    public function setUp(): void
    {
        parent::setUp();

        StateFactory::new()->count(5)->create();
        Currency::factory()->count(5)->create();

        $this->routeGroup = RouteGroup::factory()
            ->has(RouteGroupDestination::factory()->count(3), 'destinations')
            ->has(RouteGroupTruckOption::factory()->count(3), 'truckOptions')
            ->create();
    }

    public function test_it_renders_the_page(): void
    {
        $this->get(RouteGroupResource::getUrl('edit', ['record' => $this->routeGroup]))
            ->assertOk();
    }

    public function test_it_edits_a_route(): void
    {
        $new = RouteGroup::factory()
            ->has(RouteGroupDestination::factory()->count(3), 'destinations')
            ->has(RouteGroupTruckOption::factory()->count(3), 'truckOptions')
            ->create();

        Livewire::test(EditRouteGroup::class, ['record' => $this->routeGroup->getKey()])
            ->fillForm([
                'pickup_state_id' => $new->pickup_state_id,
                'truck_options' => $new->truckOptions->map->only(['truck_category_id', 'amount', 'currency_id'])->toArray(),
                'destinations' => $new->destinations->pluck('delivery_state_id')->toArray(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->routeGroup->refresh();

        $this->assertEquals($new->pickup_state_id, $this->routeGroup->pickup_state_id);

        $OLD_TRUCK_OPTIONS = 3;
        $this->assertEquals(
            $new->truckOptions()->count() + $OLD_TRUCK_OPTIONS,
            $this->routeGroup->truckOptions()->count(),
        );

        //  NOTE: It rather seems that this input works a little different than the truck options input
        //  as it doesn't append the new destinations to the existing ones but rather replaces them
        $this->assertEquals(
            $new->destinations()->count(),
            $this->routeGroup->destinations()->count(),
        );
    }
}
