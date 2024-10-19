<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Language;
use App\Http\Resources\PaymentMethodResource;
use App\Services\MyFatoorahPaymentGatewayService;
use Brick\Money\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Tests\Traits\WithCurrency;

class PaymentMethodControllerTest extends TestCase
{
    use RefreshDatabase, WithCurrency;

    public function test_it_returns_payment_methods()
    {
        $gateway = new MyFatoorahPaymentGatewayService;
        $price = Money::of(100.25, 'SAR');
        $request = Request::create(
            route('payments.methods.index', ['lang' => Language::EN]),
            'GET'
        );
        $resource = PaymentMethodResource::collection($gateway->paymentMethods($price));

        $this->postJson(route('payments.methods.index', ['lang' => Language::EN]), [
            'price' => [
                'amount' => (string) $price->getAmount(),
                'currency' => $price->getCurrency()->getCurrencyCode(),
            ],
        ])
            ->assertOk()
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }
}
