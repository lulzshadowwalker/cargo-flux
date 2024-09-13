<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, HasTranslations;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->getTranslation('title', 'en'));
            }
        });
    }

    protected $fillable = ['title', 'slug', 'content'];

    public $translatable = ['title', 'content'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
