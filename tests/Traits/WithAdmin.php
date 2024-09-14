<?php

namespace Tests\Traits;

use App\Models\User;

trait WithAdmin
{
    public function setUpWithAdmin(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }
}
