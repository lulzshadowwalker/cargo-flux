<?php

namespace App\Models;

use Altwaireb\World\Models\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteGroupDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_group_id',
        'delivery_state_id',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'delivery_state_id');
    }

    public function routeGroup(): BelongsTo
    {
        return $this->belongsTo(RouteGroup::class);
    }
}
