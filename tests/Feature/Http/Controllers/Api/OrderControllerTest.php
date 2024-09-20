<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Language;
use App\Enums\UserType;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
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
}
