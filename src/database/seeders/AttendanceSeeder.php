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
            $currentDate = Carbon::now()->subMonth();

            while ($attendanceCount < 20) {
                if (!$currentDate->isWeekend()) {
                    Attendance::factory()->create([
                        'user_id' => $user->id,
                        'clock_in' => $currentDate->copy()->setTime(9, 0),
                        'clock_out' => $currentDate->copy()->setTime(18, 0),
                        'break_start' => $currentDate->copy()->setTime(12, 0),
                        'break_end' => $currentDate->copy()->setTime(13, 0),
                    ]);
                    $attendanceCount++;
                }
                $currentDate->addDay(); // 次の日に進める
            }
        });
    }
}