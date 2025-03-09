<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $failed = Artisan::call('upsert:gadm');
        if ($failed) {
            $this->command->error('Failed to seed place data');
            return;
        }
    }
}
