<?php

namespace App\Console\Commands;

use Altwaireb\World\Database\Seeders\BaseWorldSeeder;
use Altwaireb\World\World;
use Altwaireb\World\WorldServiceProvider;
use Database\Seeders\WorldTableSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpsertWorld extends Command
{
    protected $signature = 'upsert:world';

    protected $description = 'Upserts countries and cities into the database';

    //  NOTE: This command expects you to have manually called
    //  `php artisan world:seed`
    public function handle()
    {
        $output = [];
        $failed = 0;
        exec('world:seed', $output, $failed);
        if ($failed) {
            $this->error('Failed to seed countries and currencies');
            return;
        }

        $this->info('Countries and cities upserted successfully');
    }
}
