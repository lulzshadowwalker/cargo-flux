<?php

use App\Contracts\ProfileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\SupportTicketController;
use App\Http\Controllers\Api\TruckCategoryController;
use App\Models\TruckCategory;
use Illuminate\Support\Facades\Route;


Route::post('/auth/otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::post('/auth/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('auth.logout');

Route::get('/pages', [PageController::class, 'index'])->name('page.index');
Route::get('/pages/{page}', [PageController::class, 'show'])->name('page.show');

Route::get('/faqs', [FaqController::class, 'index'])->name('faq.index');
Route::get('/faqs/{faq}', [FaqController::class, 'show'])->name('faq.show');

Route::get('/me', [ProfileController::class, 'index'])->middleware('auth:sanctum')->name('profile.index');
Route::patch('/me', [ProfileController::class, 'update'])->middleware('auth:sanctum')->name('profile.update');

Route::get('/support-tickets', [SupportTicketController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('support-tickets.index');

Route::get('/support-tickets/{supportTicket}', [SupportTicketController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('support-tickets.show');

Route::post('/support-tickets', [SupportTicketController::class, 'store'])
    ->name('support-tickets.store');


Route::get('/trucks/categories', [TruckCategoryController::class, 'index'])->name('trucks.categories.index');
Route::get('/trucks/categories/{truckCategory}', [TruckCategoryController::class, 'show'])->name('trucks.categories.show');

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });
