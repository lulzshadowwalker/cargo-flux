<?php

namespace Tests\Traits;

use App\Enums\UserType;
use App\Models\User;
use Database\Seeders\ShieldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
