<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class TruckCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'tonnage', 'length'];

    protected function casts(): array
    {
        return [
            'tonnage' => 'decimal:2',
            'length' => 'decimal:2',
        ];
    }

    public $translatable = ['name'];

    public function trucks(): HasMany
    {
        return $this->hasMany(Truck::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function image(): Attribute
    {
        return Attribute::get(fn(): string => "https://via.placeholder.com/150");
    }

    public function isAvailable(): Attribute
    {
        return Attribute::get(function () {
            return $this->trucks()->exists() && (! $this->orders()->activeOrScheduled()->exists());
        });
    }
}
