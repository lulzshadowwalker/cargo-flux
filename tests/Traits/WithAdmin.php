<?php

namespace Tests\Traits;

use App\Enums\UserType;
use App\Models\User;
use Database\Seeders\ShieldSeeder;

trait WithAdmin
{
    public function setUpWithAdmin(): void
    {
        $this->seed(ShieldSeeder::class);

        $user = User::factory()->create([
            'type' => UserType::ADMIN,
        ]);
        $user->assignRole('super_admin');
        $this->actingAs($user);
    }
}
