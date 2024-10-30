<?php

namespace Database\Factories;

use App\Models\Payment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
        ];
    }
}
