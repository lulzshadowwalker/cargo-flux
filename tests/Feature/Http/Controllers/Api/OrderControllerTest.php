<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\DriverStatus;
use App\Enums\Language;
use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Http\Resources\OrderResource;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use App\Models\TruckCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_orders()
    {
        $customer = Customer::factory()->create();
        $orders = Order::factory()->count(3)->for($customer)->create();
        $resource = OrderResource::collection($orders);
        $request = Request::create(route('orders.index', ['lang' => Language::EN]), 'GET');
        $this->actingAs($customer->user);

        $this->getJson(route('orders.index', [
            'lang' => Language::EN,
        ]))
            ->assertOk()
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }

    public function test_it_can_show_an_order()
    {
        $order = Order::factory()->create();
        $resource = OrderResource::make($order->load('customer'));
        $request = Request::create(route('orders.show', [
            'lang' => Language::EN,
            'order' => $order,
        ]), 'GET');
        $this->actingAs($order->customer->user);

        $this->getJson(route('orders.show', [
            'lang' => Language::EN,
            'order' => $order,
            'include' => 'CUSTOMER',
        ]))
            ->assertOk()
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }

    public function test_customer_cannot_show_an_order_of_another_customer()
    {
        $order = Order::factory()->create();
        $customer = Customer::factory()->create();
        $this->actingAs($customer->user);

        $this->getJson(route('orders.show', [
            'lang' => Language::EN,
            'order' => $order,
        ]))
            ->assertForbidden();
    }

    public function test_it_can_update_an_order()
    {
        $driver = Driver::factory()->for(
            User::factory()->create([
                'type' => UserType::DRIVER,
                'status' => UserStatus::ACTIVE
            ])
        )->create(['status' => DriverStatus::APPROVED]);

        $order = Order::factory()->for($driver)->create();
        $data = [
            'data' => [
                'attributes' => [
                    'currentLocation' => [
                        'latitude' => 1.0,
                        'longitude' => 1.0,
                    ],
                ]
            ]
        ];

        $this->actingAs($driver->user);

        $this->patchJson(route('orders.update', [
            'lang' => Language::EN,
            'order' => $order,
        ]), $data)
            ->assertOk();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'current_location_latitude' => 1.0,
            'current_location_longitude' => 1.0,
        ]);

        $resource = OrderResource::make($order->refresh()->load('customer', 'truck', 'driver', 'reviews'));

        $this->getJson(route('orders.show', [
            'lang' => Language::EN,
            'order' => $order,
            'include' => 'CUSTOMER,TRUCK,DRIVER,REVIEWS',
        ]))
            ->assertOk()
            ->assertExactJson(
                $resource->response()->getData(true),
            );
    }

    public function test_driver_cannot_update_an_order_of_another_driver()
    {
        $driver = Driver::factory()->for(
            User::factory()->create([
                'type' => UserType::DRIVER,
                'status' => UserStatus::ACTIVE
            ])
        )->create(['status' => DriverStatus::APPROVED]);

        $order = Order::factory()->create();
        $data = [
            'data' => [
                'attributes' => [
                    'currentLocation' => [
                        'latitude' => 1.0,
                        'longitude' => 1.0,
                    ],
                ]
            ]
        ];

        $this->actingAs($driver->user);

        $this->patchJson(route('orders.update', [
            'lang' => Language::EN,
            'order' => $order,
        ]), $data)
            ->assertForbidden();
    }

    public function test_it_creates_an_order()
    {
        $customer = Customer::factory()->create();
        $truckCategory = TruckCategory::factory()->create();
        $currency = Currency::factory()->create();

        $data = [
            'data' => [
                'attributes' => [
                    'paymentMethod' => 'DIRECT',
                    'scheduledAt' => now()->addDay()->toDateTimeString(),
                    'cargo' => 'Cargo',
                    'price' => [
                        'amount' => 100.0,
                        'currency' => $currency->code,
                    ],
                    'pickupLocation' => [
                        'latitude' => 1.0,
                        'longitude' => 1.0,
                    ],
                    'deliveryLocation' => [
                        'latitude' => 1.0,
                        'longitude' => 1.0,
                    ],
                ],
                'relationships' => [
                    'truckCategory' => [
                        'data' => [
                            'id' => $truckCategory->id,
                        ],
                    ],
                ],
            ],
        ];

        $this->actingAs($customer->user);

        $this->postJson(route('orders.store', [
            'lang' => Language::EN,
        ]), $data)
            ->assertCreated();

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'payment_method' => 'DIRECT',
            'payment_status' => 'PENDING_APPROVAL',
            'status' => 'PENDING_APPROVAL',
            'scheduled_at' => now()->addDay()->toDateTimeString(),
            'cargo' => 'Cargo',
            'amount' => 100.0,
            'currency_id' => $currency->id,
            'pickup_location_latitude' => 1.0,
            'pickup_location_longitude' => 1.0,
            'delivery_location_latitude' => 1.0,
            'delivery_location_longitude' => 1.0,
            'truck_category_id' => 1 ?? $truckCategory->id,
        ]);
    }
}
