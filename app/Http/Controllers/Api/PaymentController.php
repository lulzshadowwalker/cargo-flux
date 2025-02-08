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
            $request->payable(),
            $request->paymentMethodId(),
        );

        return PaymentResource::make($payment)->url($url);
    }

    /**
     * Handle a successful/failed payment callback from the payment gateway.
     */
    public function callback(Request $request)
    {
        $payment = $this->gateway->callback($request);

        return redirect()->away('myapp://payment?success=' . $payment->isPaid ? 'true' : 'false');
    }
}
