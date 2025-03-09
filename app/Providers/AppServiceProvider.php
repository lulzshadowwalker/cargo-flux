<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayService;
use App\Contracts\ProfileController;
use App\Contracts\PushNotificationService;
use Illuminate\Support\ServiceProvider;
use App\Http\Response\JsonResponseBuilder;
use App\Contracts\ResponseBuilder;
use App\Contracts\ReverseGeocoder;
use App\Http\Controllers\Api\CustomerProfileController;
use App\Http\Controllers\Api\DriverProfileController;
use App\Services\FirebasePushNotification\FirebasePushNotificationService;
use App\Services\GeoapifyReverseGeocoder;
use App\Services\MyFatoorahPaymentGatewayService;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Exception;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ResponseBuilder::class, JsonResponseBuilder::class);
        $this->app->bind(PushNotificationService::class, FirebasePushNotificationService::class);
        $this->app->bind(PaymentGatewayService::class, MyFatoorahPaymentGatewayService::class);
        $this->app->bind(ReverseGeocoder::class, GeoapifyReverseGeocoder::class);

        $this->app->bind(ProfileController::class, function ($app) {
            if (Auth::user()->isCustomer) {
                return $app->make(CustomerProfileController::class);
            } else if (Auth::user()->isDriver) {
                return $app->make(DriverProfileController::class);
            }

            throw new Exception('User role is not recognized');
        });

        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(config('app.supported_locales'));
        });
    }
}
