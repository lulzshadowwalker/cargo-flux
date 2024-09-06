<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Response\JsonResponseBuilder;
use App\Contracts\ResponseBuilder;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ResponseBuilder::class, JsonResponseBuilder::class);
    }

    public function boot(): void
    {
        //
    }
}
