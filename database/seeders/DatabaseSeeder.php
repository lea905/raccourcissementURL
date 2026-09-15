<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function ($user) {
            \App\Models\ShortLink::factory(rand(2, 10))->create([
                'user_id' => $user->id,
            ]);
        });

        // Create a specific user for testing if it doesn't exist
        $testUser = User::where('email', 'test@example.com')->first();
        if (!$testUser) {
            $testUser = User::factory()->create([
                'email' => 'test@example.com',
            ]);
        }
        \App\Models\ShortLink::factory(5)->create([
            'user_id' => $testUser->id,
        ]);
    }
}
