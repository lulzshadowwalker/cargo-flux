<?php

namespace App\Providers;

use App\Contract\ProfileController;
use Illuminate\Support\ServiceProvider;
use App\Http\Response\JsonResponseBuilder;
use App\Contracts\ResponseBuilder;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Exception;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ResponseBuilder::class, JsonResponseBuilder::class);

        $this->app->bind(ProfileController::class, function ($app) {
            // if (Auth::user()->isCustomer) {
            return $app->make('App\Http\Controllers\Api\CustomerProfileController');
            // }

            throw new Exception('User role is not recognized');
        });
    }

    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(config('app.supported_locales'));
        });
    }
}
