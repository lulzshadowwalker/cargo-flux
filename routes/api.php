<?php

use App\Contract\ProfileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\SupportTicketController;
use Illuminate\Support\Facades\Route;


Route::post('/auth/otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::post('/auth/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');

Route::get('/pages', [PageController::class, 'index'])->name('page.index');
Route::get('/pages/{page}', [PageController::class, 'show'])->name('page.show');

Route::get('/faqs', [FaqController::class, 'index'])->name('faq.index');
Route::get('/faqs/{faq}', [FaqController::class, 'show'])->name('faq.show');

Route::get('/me', [ProfileController::class, 'me'])->middleware('auth:sanctum')->name('me');

Route::get('/support-tickets', [SupportTicketController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('support-tickets.index');

Route::get('/support-tickets/{supportTicket}', [SupportTicketController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('support-tickets.show');

Route::post('/support-tickets', [SupportTicketController::class, 'store'])
    ->middleware('auth:sanctum')
    ->name('support-tickets.store');
