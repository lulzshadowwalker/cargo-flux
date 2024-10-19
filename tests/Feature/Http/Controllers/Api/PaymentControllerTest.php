<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Language;
use App\Enums\OrderPaymentStatus;
use App\Http\Resources\PaymentResource;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Order;
use Brick\Money\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response as HttpResponse;
use Tests\TestCase;
use Tests\Traits\WithCurrency;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase, WithCurrency;

    public function test_it_can_start_a_payment()
    {
        //
        $customer = Customer::factory()->has(
            Order::factory()->state([
                'payment_status' => OrderPaymentStatus::UNPAID,
                'price' => Money::of(100, 'USD'),
            ])
        )->create();

        $order = $customer->orders()->first();

        $token = $customer->user->createToken(config('app.name'))->plainTextToken;

        //
        $response = $this->postJson(route('payments.store', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'price' => [
                        'amount' => (string) $order->price->getAmount(),
                        'currency' => $order->price->getCurrency()->getCurrencyCode(),
                    ],
                    'details' => [
                        'paymentMethodId' => 1,
                    ]
                ],
                'relationships' => [
                    'payable' => [
                        'data' => [
                            'type' => 'order',
                            'id' => $order->id,
                        ],
                    ],
                ],
            ]
        ], ['Authorization' => "Bearer $token"]);

        //
        $this->assertDatabaseHas('payments', [
            'payable_id' => $order->id,
            'payable_type' => Order::class,
            'amount' => $order->price->getAmount(),
            'currency_id' => Currency::where('code', 'USD')->first()->id,
            'user_id' => $customer->user->id,
            'external_reference' => $response['data']['attributes']['externalReference'],
        ]);

        $resource = PaymentResource::make($order->payments()->first())->url($response['data']['meta']['url']);

        $response->assertExactJson(
            $resource->response($response->baseRequest)->getData(true),
        );

    }

    public function test_customer_can_only_pay_for_their_own_payables()
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['payment_status' => OrderPaymentStatus::UNPAID]);

        $token = $customer->user->createToken(config('app.name'))->plainTextToken;


        $response = $this->postJson(route('payments.store', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'price' => [
                        'amount' => $order->price->getAmount(),
                        'currency' => $order->price->getCurrency()->getCurrencyCode(),
                    ],
                    'details' => [
                        'paymentMethodId' => 1,
                    ]
                ],
                'relationships' => [
                    'payable' => [
                        'data' => [
                            'type' => 'order',
                            'id' => $order->id,
                        ],
                    ],
                ],
            ]
        ], ['Authorization' => "Bearer $token"]);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function test_it_can_handle_payment_callback()
    {
        //  TODO: Might have to mock the PaymentGatewayService
        $this->markTestIncomplete();
    }

    public function test_customer_can_only_pay_for_unpaid_payables()
    {
        $customer = Customer::factory()->has(
            Order::factory()->state([
                'payment_status' => OrderPaymentStatus::APPROVED,
                'price' => Money::of(100, 'USD'),
            ])
        )->create();

        $order = $customer->orders()->first();

        $token = $customer->user->createToken(config('app.name'))->plainTextToken;

        $response = $this->postJson(route('payments.store', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'price' => [
                        'amount' => $order->price->getAmount(),
                        'currency' => $order->price->getCurrency()->getCurrencyCode(),
                    ],
                    'details' => [
                        'paymentMethodId' => 1,
                    ]
                ],
                'relationships' => [
                    'payable' => [
                        'data' => [
                            'type' => 'order',
                            'id' => $order->id,
                        ],
                    ],
                ],
            ]
        ], ['Authorization' => "Bearer $token"]);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    //  FIXME: Validate that $payable->price equals the request price
    //  TODO: Implemennt Payable interface for payables an accept Payable $payable instead of object $payable ->items(): PayableItem ->price(): Money
    public function test_it_validates_the_payable_price_against_the_request_price()
    {
        //  WARNING: What about service charge ? equals ? or greater than ?
        $this->markTestIncomplete();
    }
}
