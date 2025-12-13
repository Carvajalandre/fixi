<?php

namespace Database\Factories;

use App\Models\Interaction;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interaction>
 */
class InteractionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::query()->inRandomOrder()->value('id') ?? Ticket::factory(),
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'type' => $this->faker->randomElement(['comentario', 'estado']),
            'content' => $this->faker->paragraph(),
        ];
    }
}
