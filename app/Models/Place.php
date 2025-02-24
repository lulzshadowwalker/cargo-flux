<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

class Place extends Model
{
    use HasFactory, HasSpatial;

    protected $fillable = [
        'name',
        'location',
        'boundaries',
        'country',
    ];

    protected $casts = [
        'location' => Point::class,
        'boundaries' => Polygon::class,
    ];

    protected $spatialFields = [
        'boundaries',
    ];
}
