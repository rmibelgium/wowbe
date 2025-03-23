<?php

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reading>
 */
class ReadingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dateutc' => $this->faker->dateTimeThisMonth(),
            'softwaretype' => $this->faker->word(),
            'baromin' => $this->faker->randomFloat(2, 28, 32),
            'dailyrainin' => $this->faker->randomFloat(2, 0, 10),
            'dewptf' => $this->faker->randomFloat(2, 0, 100),
            'humidity' => $this->faker->randomFloat(2, 0, 100),
            'rainin' => $this->faker->randomFloat(2, 0, 10),
            'soilmoisture' => $this->faker->randomFloat(2, 0, 100),
            'soiltempf' => $this->faker->randomFloat(2, 0, 100),
            'tempf' => $this->faker->randomFloat(2, 0, 100),
            'visibility' => $this->faker->randomFloat(2, 0, 100),
            'winddir' => $this->faker->randomFloat(2, 0, 360),
            'windspeedmph' => $this->faker->randomFloat(2, 0, 100),
            'windgustdir' => $this->faker->randomFloat(2, 0, 360),
            'windgustmph' => $this->faker->randomFloat(2, 0, 100),
            'site_id' => Site::inRandomOrder()->first(),
        ];
    }
}
