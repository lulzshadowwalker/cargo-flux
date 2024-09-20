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
        if (! array_key_exists($this->latitudeField, $attributes) || ! array_key_exists($this->longitudeField, $attributes)) {
            throw new InvalidArgumentException("The location fields are not set correctly.");
        }

        if ($attributes[$this->latitudeField] === null || $attributes[$this->longitudeField] === null) {
            return null;
        }

        return new GeoPoint(
            $attributes[$this->latitudeField],
            $attributes[$this->longitudeField]
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!$value instanceof GeoPoint) {
            if (is_array($value) && isset($value['latitude'], $value['longitude'])) {
                $value = new GeoPoint($value['latitude'], $value['longitude']);
            } else {
                throw new InvalidArgumentException("The given value is not an instance of GeoPoint.");
            }
        }

        return [
            $this->latitudeField => $value->latitude,
            $this->longitudeField => $value->longitude,
        ];
    }
}
