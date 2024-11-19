<?php

namespace App\Actions;

use Altwaireb\World\Models\State;
use App\Contracts\ReverseGeocoder;
use App\Exceptions\UnsupportedRouteException;
use App\Models\RouteGroup;
use App\Models\RouteGroupTruckOption;
use App\Models\TruckCategory;
use App\Support\GeoPoint;
use Brick\Money\Money;

class CalculateOrderPrice
{
    public function __construct(protected ReverseGeocoder $reverseGeocoder)
    {
        //
    }

    public function handle(
        GeoPoint $pickupLocation,
        GeoPoint $deliveryLocation,
        TruckCategory $truckCategory,
    ): Money {
        $pickupState = $this->reverseGeocoder->getState($pickupLocation);
        $deliveryState = $this->reverseGeocoder->getState($deliveryLocation);

        //  NOTE: This is a workaround to remove common words from the state name.
        $pickupState = preg_replace('/\b(?:Province|Governorate|Region)\b/i', '', $pickupState);

        $route = RouteGroup::where('pickup_state_id', State::where('name', 'like', "%$pickupState%")->first()?->id)
            ->whereHas('destinations', fn ($query) => $query->where('delivery_state_id', State::where('name', $deliveryState)->first()?->id))
            ->whereHas('truckOptions', fn ($query) => $query->where('truck_category_id', $truckCategory->id))
            ->first();

        if (! $route) {
            throw new UnsupportedRouteException("Route from {$pickupState} to {$deliveryState} is not supported.");
        }

        $truckOption = RouteGroupTruckOption::where('route_group_id', $route->id)
            ->where('truck_category_id', $truckCategory->id)
            ->first();

        return $truckOption->price;
    }
}
