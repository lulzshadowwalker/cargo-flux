<?php

namespace App\Observers;

use App\Enums\PaymentStatus;
use App\Events\PaymentPaid;
use App\Models\Payment;

class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        if (! $payment->status) {
            $payment->status = PaymentStatus::PENDING;
        }
    }

    public function created(Payment $payment): void
    {
        if ($payment->isPaid) {
            PaymentPaid::dispatch($payment);
        }
    }

    public function updated(Payment $payment): void
    {
        if ($payment->isDirty('status') && $payment->isPaid) {
            PaymentPaid::dispatch($payment);
        }
    }
}
