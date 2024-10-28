<?php

namespace App\Services;

use App\Contracts\ReverseGeocoder;
use Illuminate\Support\Facades\Http;
use App\Support\GeoPoint;
use Exception;
use Illuminate\Support\Facades\Log;

//  TODO:
//  WARNING: Geoapify requires a paid subscription to use their API for commercial use.
class GeoapifyReverseGeocoder implements ReverseGeocoder
{
    public function getState(GeoPoint $geoPoint): string
    {
        Log::info('Getting state from Geoapify', ['geo_point' => $geoPoint]);

        $response = Http::get("https://api.geoapify.com/v1/geocode/reverse", [
            'lat' => $geoPoint->latitude,
            'lon' => $geoPoint->longitude,
            'type' => 'state',
            'format' => 'json',
            'apiKey' => config('services.geoapify.api_key'),
        ]);

        if (! $response->successful()) {
            Log::error('Failed to get county from Geoapify', [
                'geo_point' => $geoPoint,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new Exception('Failed to get county from Geoapify');
        }

        return $response->json()['results'][0]['state'];
    }
}
