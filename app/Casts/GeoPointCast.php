<?php

namespace App\Casts;

use App\Support\GeoPoint;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class GeoPointCast implements CastsAttributes
{
    public function __construct(
        protected string $latitudeField,
        protected string $longitudeField,
    ) {
        //
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!isset($attributes[$this->latitudeField], $attributes[$this->longitudeField])) {
            throw new InvalidArgumentException("The location fields are not set correctly.");
        }

        return new GeoPoint(
            $attributes[$this->latitudeField],
            $attributes[$this->longitudeField]
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!$value instanceof GeoPoint) {
            throw new InvalidArgumentException("The given value is not an instance of GeoPoint.");
        }

        return [
            $this->latitudeField => $value->latitude,
            $this->longitudeField => $value->longitude,
        ];
    }
}
