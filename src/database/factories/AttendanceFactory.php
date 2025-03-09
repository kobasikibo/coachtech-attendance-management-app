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
            'status' => Attendance::STATUS_CLOCKED_OUT,
            'approval_status' => $this->faker->randomElement([
                Attendance::APPROVAL_REGISTERED,
                Attendance::APPROVAL_PENDING,
                Attendance::APPROVAL_APPROVED,
            ]),
        ];
    }
}
