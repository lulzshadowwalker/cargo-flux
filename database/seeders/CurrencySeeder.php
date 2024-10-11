<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $failed = Artisan::call('upsert:currencies');
        if ($failed) {
            $this->command->error('Failed to seed currencies');
        }
    }
}
