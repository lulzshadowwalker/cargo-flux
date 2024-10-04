<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'email' => 'bayanata.dvteam@gmail.com',
            'password' => bcrypt('eOXyZj6uoykYVS8'),
            'status' => UserStatus::ACTIVE,
            'type' => UserType::ADMIN,
        ]);

        // FIXME: This probably seeds the production database, not the sandbox database!
        if (Artisan::call('shield:super-admin --user=' . $admin->id)) {
            $this->command->error('Failed to make user super admin');
            return;
        }
    }
}
