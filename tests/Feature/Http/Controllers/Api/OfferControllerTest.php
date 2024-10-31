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
}
