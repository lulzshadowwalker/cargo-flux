<?php

namespace App\Console\Commands;

use App\Services\GeoapifyReverseGeocoder;
use App\Support\GeoPoint;
use Illuminate\Console\Command;

class TestGeoapify extends Command
{
    protected $signature = 'app:test-geoapify';

    protected $description = 'Command description';

    public function handle()
    {
        $geocoder = new GeoapifyReverseGeocoder;
        $riyadh = new GeoPoint(24.7136, 46.6753);
        $geocoder->getState($riyadh);
    }
}
