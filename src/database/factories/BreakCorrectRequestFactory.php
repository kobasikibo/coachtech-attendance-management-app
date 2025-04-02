<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BreakCorrectRequest>
 */
class BreakCorrectRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'break_start' => $this->faker->dateTimeThisYear(),
            'break_end' => $this->faker->dateTimeThisYear(),
        ];
    }
}
