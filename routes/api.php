<?php

use App\Contracts\ResponseBuilder;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Middleware\DomainMiddleware;
use App\Http\Middleware\SandboxMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware([
    // DomainMiddleware::class,
    SandboxMiddleware::class,
])->group(function () {
    Route::post('/auth/otp/send', [OtpController::class, 'send']);
    Route::post('/auth/otp/verify', [OtpController::class, 'verify']);
    Route::post('/auth/register', [AuthController::class, 'register']);
});
