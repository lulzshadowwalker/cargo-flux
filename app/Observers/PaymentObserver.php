<?php

namespace App\Observers;

use App\Enums\PaymentStatus;
use App\Models\Payment;

class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        if (! $payment->status) {
            $payment->status = PaymentStatus::PENDING;
        }
    }
}
