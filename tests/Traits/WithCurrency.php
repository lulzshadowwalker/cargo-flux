<?php

namespace Tests\Traits;

use Database\Seeders\CurrencySeeder;

trait WithCurrency
{
    public function setUpWithCurrency(): void
    {
        $this->seed(CurrencySeeder::class);
    }
}
