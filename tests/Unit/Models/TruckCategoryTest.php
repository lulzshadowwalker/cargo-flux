<?php

namespace Tests\Unit\Models;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Truck;
use App\Models\TruckCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TruckCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_available_attribute_returns_true_only_if_the_truck_category_is_available()
    {
        $expected = [
            OrderStatus::PENDING_DRIVER_ASSIGNMENT,
            OrderStatus::PENDING_APPROVAL,
            OrderStatus::COMPLETED,
            OrderStatus::CANCELED,
        ];

        // with attached trucks
        collect(OrderStatus::cases())->each(function ($status) {
            $category = TruckCategory::factory()->create();
            $truck = Truck::factory()->for($category, 'category')->create();
            Order::Factory()
                ->for($truck)
                ->for($category)
                ->create(['status' => $status]);
        });

        // // without attached trucks/drivers
        collect(OrderStatus::cases())->each(function ($status) {
            TruckCategory::factory()
                ->has(Order::factory()->state([
                    'status' => $status,
                    'truck_id' => null,
                ]))
                ->create();
        });

        $actual = TruckCategory::all()->filter(fn($category) => $category->isAvailable);

        $this->assertCount(count($expected), $actual);
    }
}
