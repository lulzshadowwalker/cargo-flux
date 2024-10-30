<?php

namespace Tests\Unit\Models;

use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_unique_random_number_is_automatically_generated_when_creating_a_new_invoice()
    {
        $invoice = Invoice::factory()->create(['number' => null]);

        $this->assertNotNull($invoice->number);
    }
}
