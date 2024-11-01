<?php

use App\Livewire\Website\Home;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('website.home');
