<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Pulse\Facades\Pulse;

class PulseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('viewPulse', fn(User $user) => $user->isAdmin);

        Pulse::user(fn($user) => [
            'name' => $user->fullName,
            'extra' => $user->phone,
            'avatar' => 'https://images.unsplash.com/photo-1519058082700-08a0b56da9b4?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NTR8fHBvcnRyYWl0fGVufDB8fDB8fHww',
        ]);
    }
}
