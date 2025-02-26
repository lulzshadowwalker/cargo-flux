<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Truck extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'license_plate',
        'driver_id',
        'truck_category_id',
        'is_personal_property',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'driver_id' => 'integer',
            'is_personal_property' => 'boolean',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TruckCategory::class, 'truck_category_id');
    }

    const MEDIA_COLLECTION_LICENSE = "license";
    const MEDIA_COLLECTION_IMAGES = "images";
    const MEDIA_COLLECTION_AUTHORIZATION_CLAUSE = "authorization-clause";

    public function registerMediaCollections(): void
    {
        //  NOTE: multiple images i.e. front, back, left side, and right side.
        $this->addMediaCollection(self::MEDIA_COLLECTION_IMAGES);

        $this->addMediaCollection(self::MEDIA_COLLECTION_LICENSE)
            ->singleFile();

        $this->addMediaCollection(self::MEDIA_COLLECTION_AUTHORIZATION_CLAUSE)
            ->singleFile();
    }

    /**
     * Get the truck's license URL.
     */
    public function license(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMediaUrl(self::MEDIA_COLLECTION_LICENSE) ?: null
        );
    }

    /**
     * Get the truck's driver license file.
     */
    public function licenseFile(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMedia(self::MEDIA_COLLECTION_LICENSE) ?: null
        );
    }

    /**
     * Get the truck's authorization clause URL.
     */
    public function authorizationClause(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMediaUrl(self::MEDIA_COLLECTION_AUTHORIZATION_CLAUSE) ?: null
        );
    }

    /**
     * Get the truck's authorization clause file.
     */
    public function authorizationClauseFile(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMedia(self::MEDIA_COLLECTION_AUTHORIZATION_CLAUSE) ?: null
        );
    }

    /**
     * Get the truck's images.
     */
    public function images(): Attribute
    {
        return Attribute::get(
            fn() => $this->getMedia(self::MEDIA_COLLECTION_IMAGES)
        );
    }

    /**
     * Get the truck's images URLs.
     */
    public function imagesUrls(): Attribute
    {
        return Attribute::get(
            fn() => $this->getMedia(self::MEDIA_COLLECTION_IMAGES)->map(fn($media) => $media->getUrl())
        );
    }
}
