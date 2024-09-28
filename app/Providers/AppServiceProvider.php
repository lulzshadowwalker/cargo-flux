<?php

namespace App\Providers;

use App\Contracts\ProfileController;
use App\Contracts\PushNotificationService;
use Illuminate\Support\ServiceProvider;
use App\Http\Response\JsonResponseBuilder;
use App\Contracts\ResponseBuilder;
use App\Http\Controllers\Api\CustomerProfileController;
use App\Http\Controllers\Api\DriverProfileController;
use App\Services\FirebasePushNotification\FirebasePushNotificationService;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Exception;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ResponseBuilder::class, JsonResponseBuilder::class);
        $this->app->bind(PushNotificationService::class, FirebasePushNotificationService::class);

        $this->app->bind(ProfileController::class, function ($app) {
            if (Auth::user()->isCustomer) {
                return $app->make(CustomerProfileController::class);
            } else if (Auth::user()->isDriver) {
                return $app->make(DriverProfileController::class);
            }

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
