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
        $clicks = fake()->boolean(80) ? fake()->numberBetween(1, 1000) : 0;
        
        return [
            'original_url' => fake()->url(),
            'short_code' => \Illuminate\Support\Str::random(6),
            'clicks_count' => $clicks,
            'last_visited_at' => $clicks > 0 ? fake()->dateTimeBetween('-2 months', 'now') : null,
            'expires_at' => fake()->optional(0.3)->dateTimeBetween('-1 months', '+6 months'),
        ];
    }
}
