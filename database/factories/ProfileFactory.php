<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'age' => fake()->numberBetween(18, 65),
            'gender' => fake()->randomElement(['female', 'male', 'non-binary']),
            // High-resolution real-face portraits from the free pravatar.cc set
            // (img 1–70). Used purely for demo seed data.
            'photo_url' => 'https://i.pravatar.cc/600?img='.fake()->unique()->numberBetween(1, 70),
            'bio' => fake()->paragraph(),
        ];
    }
}
