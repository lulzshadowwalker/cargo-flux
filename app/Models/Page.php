<?php

namespace App\Models;

use App\Observers\PageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

#[ObservedBy(PageObserver::class)]
class Page extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'slug', 'content'];

    public $translatable = ['title', 'content'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
