<?php

namespace App\Contracts;

use App\Models\Payment;
use Brick\Money\Money;
use App\Support\PaymentMethod;
use Illuminate\Http\Request;

interface PaymentGatewayService
{
    /**
     * Get the available payment methods for the given price
     *
     * @param Money $price
     *
     * @return array<PaymentMethod>
     */
    public function paymentMethods(Money $price): array;

    /**
     * Start the payment process
     *
     * @param Money $price
     * @param string $paymentMethodId
     * @param Payable $payable
     *
     * @return array of two elements first one being a Payment object
     * and the second one being the payment url returned from the gateway
     */
    public function start(Payable $payable, string $paymentMethodId): array;

    /**
     * Handle the success/failure callbacks
     */
    public function callback(Request $request): Payment;
}
