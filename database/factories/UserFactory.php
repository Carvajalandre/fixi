<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'), // Default password
            'role_id' => Role::query()->inRandomOrder()->value('id') ?? Role::factory(),
            'area_id' => Area::query()->inRandomOrder()->value('id') ?? Area::factory(),
        ];
    }
}
