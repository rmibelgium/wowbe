<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'longitude' => $this->faker->longitude(),
            'latitude' => $this->faker->latitude(),
            'height' => $this->faker->numberBetween(1, 100),
            'timezone' => $this->faker->timezone(),
            'auth_key' => $this->faker->numerify('######'),
            'user_id' => User::factory(),
        ];
    }
}
