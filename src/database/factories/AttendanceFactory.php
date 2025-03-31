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
            'date' => $this->faker->date(),
            'clock_in' => $this->faker->dateTimeBetween('08:00', '10:00'),
            'clock_out' => $this->faker->dateTimeBetween('17:00', '20:00'),
            'status' => Attendance::STATUS_CLOCKED_OUT,
            'approval_status' => $this->faker->randomElement([
                Attendance::APPROVAL_REGISTERED,
                Attendance::APPROVAL_PENDING,
                Attendance::APPROVAL_APPROVED,
            ]),
        ];
    }
}
