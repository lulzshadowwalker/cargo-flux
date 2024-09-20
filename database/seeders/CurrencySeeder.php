<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        // NOTE: It is important for testing in the sandbox environment to seed all currencies.
        Currency::factory()->count(180)->create();
    }
}
