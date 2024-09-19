<?php

namespace App\Support;

class GeoPoint
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {
        //
    }
}
