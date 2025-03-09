<?php

namespace App\Support;

use MatanYadaev\EloquentSpatial\Objects\Point;

class GeoPoint
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {
        //
    }

    public function toBoundaryPoint(): Point
    {
        return new Point($this->latitude, $this->longitude);
    }
}
