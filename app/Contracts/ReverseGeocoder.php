<?php

namespace App\Contracts;

use App\Support\GeoPoint;

interface ReverseGeocoder
{
    /**
     * Get the state of the given GeoPoint.
     */
    public function getState(GeoPoint $geoPoint): string;
}
