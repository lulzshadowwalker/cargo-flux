<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteGroupTruckOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_group_id',
        'truck_category_id',
        'amount',
        'currency_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => MoneyCast::class,
        ];
    }

    public function routeGroup(): BelongsTo
    {
        return $this->belongsTo(RouteGroup::class);
    }

    public function truckCategory(): BelongsTo
    {
        return $this->belongsTo(TruckCategory::class);
    }
}
