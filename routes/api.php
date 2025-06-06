<?php

use App\Contracts\ProfileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderPreviewController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\SupportTicketController;
use App\Http\Controllers\Api\TruckCategoryController;
use App\Http\Controllers\Api\UserPreferenceController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::post('/auth/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('auth.logout');

Route::post('/referrals', [ReferralController::class, 'store'])->middleware('auth:sanctum')->name('referrals.store');

Route::get('/pages', [PageController::class, 'index'])->name('page.index');
Route::get('/pages/{page}', [PageController::class, 'show'])->name('page.show');

Route::get('/faqs', [FaqController::class, 'index'])->name('faq.index');
Route::get('/faqs/{faq}', [FaqController::class, 'show'])->name('faq.show');

Route::get('/me', [ProfileController::class, 'index'])->middleware('auth:sanctum')->name('profile.index');
Route::get('/me/wallets/rewards', [WalletController::class, 'rewards'])->middleware('auth:sanctum')->name('profile.wallets.rewards');
Route::patch('/me', [ProfileController::class, 'update'])->middleware('auth:sanctum')->name('profile.update');

Route::get('/me/preferences', [UserPreferenceController::class, 'index'])->middleware('auth:sanctum')->name('profile.preferences.index');
Route::patch('/me/preferences', [UserPreferenceController::class, 'update'])->middleware('auth:sanctum')->name('profile.preferences.update');

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    //  TODO: Add tests to POST /orders/preview
    Route::post('/orders/preview', [OrderPreviewController::class, 'index'])->name('orders.preview')->withoutMiddleware('auth:sanctum');

    // NOTE: Do not switch the order of the /orders/current and /orders/{order} routes
    Route::patch('/orders/current', [OrderController::class, 'updateCurrent'])->name('orders.update-current');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/{order}', [OfferController::class, 'show'])->name('offers.show');
    Route::post('/offers/{order}/accept', [OfferController::class, 'accept'])->name('offers.accept');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy.all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy.single');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::patch('/notifications/read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
});

Route::post('/payments/methods', [PaymentMethodController::class, 'index'])->name('payments.methods.index');
Route::post('/payments', [PaymentController::class, 'store'])->middleware('auth:sanctum')->name('payments.store');
Route::get('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');
