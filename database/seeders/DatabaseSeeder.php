<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with demo users, profiles and a few
     * seeded conversations so the app is populated on first run.
     */
    public function run(): void
    {
        // A known account the reviewer can log in with (password: "password").
        $alice = User::factory()
            ->has(Profile::factory()->state([
                'age' => 29,
                'gender' => 'female',
                'photo_url' => 'https://xsgames.co/randomusers/assets/avatars/female/68.jpg',
                'bio' => 'Coffee, hiking and bad puns. Say hi!',
            ]))
            ->create([
                'name' => 'Alice',
                'email' => 'alice@example.com',
            ]);

        // 60 more browsable users (≈5 pages at 12 per page), each with a profile.
        $others = User::factory()
            ->count(60)
            ->has(Profile::factory())
            ->create();

        // Seed a few conversations between Alice and the first three users.
        foreach ($others->take(3) as $other) {
            $conversation = Conversation::factory()->between($alice, $other)->create();

            $participants = [$alice, $other];

            foreach (range(1, 4) as $i) {
                Message::factory()->create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $participants[$i % 2]->id,
                ]);
            }
        }
    }
}
