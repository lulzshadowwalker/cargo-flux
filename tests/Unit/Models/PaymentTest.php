<?php

namespace Tests\Unit\Models;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Notifications\InvoicePaid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_assigns_pending_status_if_none_is_specified()
    {
        $payment = Payment::factory()->pending()->create();

        $this->assertEquals(PaymentStatus::PENDING, $payment->status);
    }

    public function test_an_invoice_is_sent_when_payment_status_is_updated_to_paid()
    {
        Notification::fake();

        $payment = Payment::factory()->pending()->create();
        $payment->update(['status' => PaymentStatus::PAID]);

        Notification::assertSentTo(
            $payment->payable->payer(),
            InvoicePaid::class
        );
    }

    public function test_an_invoice_is_sent_when_payment_is_created_with_paid_status()
    {
        Notification::fake();

        $payment = Payment::factory()->paid()->create();

        Notification::assertSentTo(
            $payment->payable->payer(),
            InvoicePaid::class
        );
    }
}
