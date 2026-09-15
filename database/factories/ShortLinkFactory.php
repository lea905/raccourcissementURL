<?php

namespace Database\Factories;

use App\Models\ShortLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShortLink>
 */
class ShortLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'original_url' => fake()->url(),
            'short_code' => \Illuminate\Support\Str::random(6),
            'clicks_count' => fake()->numberBetween(0, 1000),
            'last_visited_at' => fake()->optional(0.7)->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
