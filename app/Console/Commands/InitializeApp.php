<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitializeApp extends Command
{
    protected $signature = 'app:init';

    protected $description = 'Initialize the application';

    public function handle()
    {
        $this->info('Initializing application for production...');

        Artisan::call('upsert:pages');
        $this->info('Pages upserted successfully.');

        Artisan::call('upsert:currencies');
        $this->info('Currencies upserted successfully.');

        Artisan::call('upsert:world');
        $this->info('Countries and cities upserted successfully.');

        Artisan::call('upsert:gadm');
        $this->info('GADM upserted successfully.');

        $this->info('⏺ Application initialized successfully.');
    }
}
