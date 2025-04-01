<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $date = Carbon::today()->subDays(rand(0, 30));

        return [
            'date' => $date,
            'clock_in' => $date->copy()->setTime(rand(8, 10), rand(0, 59), rand(0, 59)),
            'clock_out' => $date->copy()->setTime(rand(17, 20), rand(0, 59), rand(0, 59)),
            'status' => Attendance::STATUS_CLOCKED_OUT,
            'approval_status' => $this->faker->randomElement([
                Attendance::APPROVAL_REGISTERED,
                Attendance::APPROVAL_PENDING,
                Attendance::APPROVAL_APPROVED,
            ]),
        ];
    }

    public function forDate(Carbon $date)
    {
        return $this->state(
            function () use ($date) {
            return [
                'date' => $date,
                'clock_in' => $date->copy()->setTime(rand(8, 10), rand(0, 59), rand(0, 59)),
                'clock_out' => $date->copy()->setTime(rand(17, 20), rand(0, 59), rand(0, 59)),
                ];
            }
        );
    }
}