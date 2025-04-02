<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function ($user) {
            $attendanceCount = 0;
            $currentDate = Carbon::yesterday();

            while ($attendanceCount < 20) {
                if (!$currentDate->isWeekend()) {
                    Attendance::factory()->create([
                        'user_id' => $user->id,
                        'date' => $currentDate->toDateString(),
                        'clock_in' => $currentDate->copy()->setTime(9, 0),
                        'clock_out' => $currentDate->copy()->setTime(18, 0),
                    ]);

                    $attendanceCount++;
                }
                $currentDate->subDay();
            }
        });
    }
}
