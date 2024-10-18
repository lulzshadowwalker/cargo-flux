<?php

namespace App\Http\Controllers\Api;

use App\Contracts\PaymentGatewayService;
use App\Http\Requests\GetPaymentMethodsRequest;
use App\Http\Resources\PaymentMethodResource;
use Brick\Money\Money;

class PaymentMethodController extends ApiController
{
    public function __construct(protected PaymentGatewayService $gateway)
    {
        //
    }

    public function index(GetPaymentMethodsRequest $request)
    {
        $methods = $this->gateway->paymentMethods(Money::of(
            $request->input('price.amount'),
            $request->input('price.currency')
        ));

        return PaymentMethodResource::collection($methods);
    }
}
