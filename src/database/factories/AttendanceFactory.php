<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        return [
            'clock_in' => $this->faker->dateTimeThisYear(),
            'clock_out' => $this->faker->dateTimeThisYear(),
            'break_start' => $this->faker->dateTimeThisYear(),
            'break_end' => $this->faker->dateTimeThisYear(),
        ];
    }
}
