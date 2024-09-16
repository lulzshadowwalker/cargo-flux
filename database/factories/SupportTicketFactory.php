<?php

namespace Database\Factories;

use App\Enums\SupportTicketStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupportTicket>
 */
class SupportTicketFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph(rand(1, 5)),
            'status' => $this->faker->randomElement(SupportTicketStatus::cases())->value,
            'phone' => $this->faker->phoneNumber,
            'name' => $this->faker->name,
            'user_id' => rand(0, 1) ? null : User::factory(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
