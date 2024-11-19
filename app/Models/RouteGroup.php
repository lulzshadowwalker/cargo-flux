<?php

namespace App\Models;

use Altwaireb\World\Models\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class RouteGroup extends Model
{
    use HasFactory;

    protected $fillable = ['pickup_state_id'];

    public function pickupState(): BelongsTo
    {
        return $this->belongsTo(State::class, 'pickup_state_id');
    }

    public function destinations(): HasMany
    {
        return $this->hasMany(RouteGroupDestination::class, 'route_group_id');
    }

    /**
     * Get the states associated with the route group as destinations.
     *
     * @return HasManyThrough
     */
    public function states(): HasManyThrough
    {
        return $this->hasManyThrough(State::class, RouteGroupDestination::class, 'route_group_id', 'id', 'id', 'delivery_state_id');
    }

    public function truckOptions(): HasMany
    {
        return $this->hasMany(RouteGroupTruckOption::class, 'route_group_id');
    }

    public function orders(): HasMany
    {
        //  TODO: Implement routeGroup.orders relationship
        throw new \Exception('Unimplemented method');
    }
}
