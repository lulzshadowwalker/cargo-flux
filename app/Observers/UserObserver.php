<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user): void
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        $user->referral_code = $code;
    }

    public function created(User $user): void
    {
        // TODO: Add unit test
        $user->preferences()->create();
    }
}
