<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class WorldSeeder extends Seeder
{
    public function run(): void
    {
        $failed = Artisan::call('upsert:world');
        if ($failed) {
            $this->command->error('Failed to seed world data');
            return;
        }
    }
}
