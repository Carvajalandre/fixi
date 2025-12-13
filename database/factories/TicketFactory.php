<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    public function definition(): array
    {
        // Obtener los IDs de usuarios existentes
        $userIds = User::pluck('id')->toArray();
        $statusIds = TicketStatus::pluck('id')->toArray();

        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'requester_id' => count($userIds) ? $this->faker->randomElement($userIds) : User::factory()->create()->id,
            'assigned_support_id' => count($userIds) ? $this->faker->randomElement($userIds) : null,
            'status_id' => count($statusIds) ? $this->faker->randomElement($statusIds) : TicketStatus::factory()->create()->id,
        ];
    }
}
