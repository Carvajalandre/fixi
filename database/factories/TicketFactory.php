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
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $usersIds = User::pluck('id')->toArray();
        $statusIds = TicketStatus::pluck('id')->toArray();

        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(),
            'requester_id' => $this->faker->randomElement($users) ?? User::factory(),
            'assigned_support_id' => $this->faker->randomElement($users) ?? User::factory(),
            'status_id' => TicketStatus::query()->inRandomOrder()->value('id') ?? TicketStatus::factory(),
        ];
    }
}
