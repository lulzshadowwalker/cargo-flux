<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpsertWorld extends Command
{
    protected $signature = 'upsert:world';
    protected $description = 'Upserts countries, states, and cities into the database';

    public function handle()
    {
        $output = [];
        $failed = 0;
        exec('php artisan world:seeder', $output, $failed);

        $successfullySeeded = $this->checkSeedingSuccess($output);

        if (!$successfullySeeded) {
            $this->error('Failed to seed countries, states, or cities.');
            return;
        }

        $this->info('Countries, states, and cities upserted successfully.');
    }

    /**
     * Check seeding success by looking for specific output patterns.
     *
     * @param array $output
     * @return bool
     */
    protected function checkSeedingSuccess(array $output): bool
    //  NOTE: Checking the returned exit code seems to be unreliable, so we're checking the output instead.
    {
        $countriesSeeded = false;
        $statesSeeded = false;

        foreach ($output as $line) {
            if (strpos($line, 'Countries Data Seeded has successful') !== false) {
                $countriesSeeded = true;
            }
            if (strpos($line, 'States Data Seeded has successful') !== false) {
                $statesSeeded = true;
            }
        }

        return $countriesSeeded && $statesSeeded;
    }
}
