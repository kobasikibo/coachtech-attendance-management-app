<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceCorrectRequest>
 */
class AttendanceCorrectRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clock_in' => $this->faker->dateTimeBetween('08:00', '10:00'),
            'clock_out' => $this->faker->dateTimeBetween('17:00', '20:00'),
        ];
    }
}
