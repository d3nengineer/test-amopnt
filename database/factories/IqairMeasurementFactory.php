<?php

namespace Database\Factories;

use App\Enums\IqairCity;
use App\Enums\IqairCountry;
use App\Enums\IqairState;
use App\Models\IqairMeasurement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IqairMeasurement>
 */
class IqairMeasurementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pollutionTimestamp = fake()->dateTimeBetween('-1 week');

        return [
            'city' => IqairCity::Yekaterinburg,
            'state' => IqairState::Sverdlovsk,
            'country' => IqairCountry::Russia,
            'latitude' => fake()->latitude(56.7, 57.0),
            'longitude' => fake()->longitude(60.4, 60.8),
            'pollution_ts' => $pollutionTimestamp,
            'aqius' => fake()->numberBetween(1, 150),
            'mainus' => fake()->randomElement(['p1', 'p2', 'o3']),
            'aqicn' => fake()->numberBetween(1, 100),
            'maincn' => fake()->randomElement(['p1', 'p2', 'o3']),
            'weather_ts' => $pollutionTimestamp,
            'temperature' => fake()->numberBetween(-30, 35),
            'pressure' => fake()->numberBetween(950, 1050),
            'humidity' => fake()->numberBetween(10, 95),
            'wind_speed' => fake()->randomFloat(2, 0, 20),
            'wind_direction' => fake()->numberBetween(0, 359),
            'weather_icon' => fake()->randomElement(['01d', '02d', '03d', '10d']),
        ];
    }
}
