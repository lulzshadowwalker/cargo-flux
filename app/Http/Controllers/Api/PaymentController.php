<?php

namespace App\Http\Controllers\Api;

use App\Contracts\PaymentGatewayService;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;

class PaymentController extends ApiController
{
    public function __construct(protected PaymentGatewayService $gateway)
    {
        //
    }

    public function store(StorePaymentRequest $request)
    {
        $this->authorize('pay', $request->payable());

        [$payment, $url] = $this->gateway->start(
            $request->price(),
            $request->paymentMethodId(),
            $request->payable(),
        );

        return PaymentResource::make($payment)->url($url);
    }

    /**
     * Handle a successful/failed payment callback from the payment gateway.
     */
    public function callback(Request $request)
    {
        $this->gateway->callback($request);
    }
}
