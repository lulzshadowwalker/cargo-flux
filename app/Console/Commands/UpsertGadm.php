<?php

namespace App\Console\Commands;

use App\Models\Place;
use Illuminate\Console\Command;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

class UpsertGadm extends Command
{
    protected $signature = 'upsert:gadm';

    protected $description = 'Upserts GADM data located in storage/gadm (geo polygons)';

    public function handle()
    {
        if (! file_exists(storage_path('gadm'))) {
            $this->Error('GADM data not found in storage/gadm');
            return;
        }

        $this->info('Upserting GADM data');
        $files = glob(storage_path('gadm') . '/*.json');
        $this->info('Found ' . count($files) . ' files in storage/gadm');

        foreach ($files as $key => $file) {
            $this->info('Upserting ' . $file . ' ' . $key + 1 . ' of ' . count($files));
            $json = json_decode(file_get_contents($file), true);

            foreach ($json['features'] as $key => $feature) {
                $this->info('Upserting ' . $feature['properties']['NAME_2'] . ' ' .  $key + 1 . ' of ' . count($json['features']));

                $points = array_map(
                    fn($coordinate) => new Point($coordinate[1], $coordinate[0]),
                    $feature['geometry']['coordinates'][0][0],
                );

                $linestring = new LineString($points);

                 Place::firstOrCreate([
                    'name' => $feature['properties']['NAME_2'],
                    'boundaries' => new Polygon([$linestring]),
                    'country' => $feature['properties']['GID_0'],
                ]);
            }
        }

        $this->info('GADM data upserted successfully');
    }
}
