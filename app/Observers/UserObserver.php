<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        // TODO: Add unit test
        $user->preferences()->create();
    }
}
