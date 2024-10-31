<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Language;
use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Truck;
use App\Models\TruckCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class OfferControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_only_eligible_offers(): void
    {
        $driver = Driver::factory()->create();
        $truckCategory = TruckCategory::factory()->create();
        Truck::factory()->for($driver)->for($truckCategory, 'category')->create();

        Order::factory()->count(3)->create();

        collect(OrderStatus::cases())->reject(fn($status) => $status === OrderStatus::PENDING_DRIVER_ASSIGNMENT)
            ->each(function (OrderStatus $status) use ($truckCategory) {
                Order::factory()->create([
                    'truck_category_id' => $truckCategory->id,
                    'status' => $status,
                ]);
            });

        $eligibleOffer = Order::factory()->create([
            'truck_category_id' => $truckCategory->id,
            'status' => OrderStatus::PENDING_DRIVER_ASSIGNMENT,
            'driver_id' => null,
        ]);

        $request = Request::create(route('offers.index', ['lang' => Language::EN]), 'GET');
        $resource = OrderResource::collection([$eligibleOffer]);

        $this->actingAs($driver->user);

        $this->getJson(route('offers.index', ['lang' => Language::EN]))
            ->assertOk()
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }

    public function test_it_accepts_offer(): void
    {
        $driver = Driver::factory()->create();
        $truckCategory = TruckCategory::factory()->create();
        Truck::factory()->for($driver)->for($truckCategory, 'category')->create();

        $offer = Order::factory()->create([
            'truck_category_id' => $truckCategory->id,
            'status' => OrderStatus::PENDING_DRIVER_ASSIGNMENT,
            'driver_id' => null,
        ]);

        $this->actingAs($driver->user);

        $response = $this->postJson(route('offers.accept', ['lang' => Language::EN, 'order' => $offer]))
            ->assertOk();

        $offer->refresh();
        $this->assertEquals(OrderStatus::DRIVER_ASSIGNED, $offer->status);
        $this->assertEquals($driver->id, $offer->driver_id);

        $resource = OrderResource::make($offer);

        $response->assertExactJson(
            $resource->response([])->getData(true),
        );
    }

    public function test_driver_with_an_active_order_cannot_accpet_an_offer(): void
    {

        $driver = Driver::factory()->create();
        $truckCategory = TruckCategory::factory()->create();
        Truck::factory()->for($driver)->for($truckCategory, 'category')->create();

        $offer = Order::factory()->create([
            'truck_category_id' => $truckCategory->id,
            'status' => OrderStatus::PENDING_DRIVER_ASSIGNMENT,
            'driver_id' => null,
        ]);

        Order::factory()->for($driver)->create([
            'status' => OrderStatus::DRIVER_ASSIGNED,
            'driver_id' => $driver->id,
        ]);

        $this->actingAs($driver->user);

        $response = $this->postJson(route('offers.accept', ['lang' => Language::EN, 'order' => $offer]))
            ->assertForbidden();
    }
}
