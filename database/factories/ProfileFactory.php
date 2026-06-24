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
        $gender = fake()->randomElement(['female', 'male', 'non-binary']);

        return [
            'user_id' => User::factory(),
            'age' => fake()->numberBetween(18, 65),
            'gender' => $gender,
            'photo_url' => $this->portrait($gender),
            'bio' => fake()->paragraph(),
        ];
    }

    /**
     * A gender-matched real-face portrait from the free, gender-tagged
     * xsgames.co avatar set (female/male, indices 0–78). Non-binary profiles
     * draw from either pool. Used purely for demo seed data.
     */
    private function portrait(string $gender): string
    {
        $pool = match ($gender) {
            'female' => 'female',
            'male' => 'male',
            default => fake()->randomElement(['female', 'male']),
        };

        $index = fake()->unique()->numberBetween(0, 78);

        return "https://xsgames.co/randomusers/assets/avatars/{$pool}/{$index}.jpg";
    }
}
