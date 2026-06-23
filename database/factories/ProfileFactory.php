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
     * A consistent, gender-appropriate portrait from the free randomuser.me
     * photo set (used purely for demo seed data).
     */
    private function portrait(string $gender): string
    {
        $pool = $gender === 'male' ? 'men' : 'women';
        $index = fake()->unique()->numberBetween(0, 98);

        return "https://randomuser.me/api/portraits/{$pool}/{$index}.jpg";
    }
}
