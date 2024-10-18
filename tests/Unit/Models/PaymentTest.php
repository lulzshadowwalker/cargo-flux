<?php

namespace Tests\Unit\Models;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_assigns_pending_status_if_none_is_specified()
    {
        $payment = Payment::factory()->pending()->create();

        $this->assertEquals(PaymentStatus::PENDING, $payment->status);
    }
}
