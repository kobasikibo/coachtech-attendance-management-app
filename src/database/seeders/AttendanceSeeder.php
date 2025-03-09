<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakModel;
use App\Models\AttendanceCorrectRequest;
use App\Models\BreakCorrectRequest;
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
                    $attendance = Attendance::factory()->create([
                        'user_id' => $user->id,
                        'date' => $currentDate->toDateString(),
                        'clock_in' => $currentDate->copy()->setTime(9, 0),
                        'clock_out' => $currentDate->copy()->setTime(18, 0),
                    ]);

                    BreakModel::factory()->create([
                        'attendance_id' => $attendance->id,
                        'break_start' => $currentDate->copy()->setTime(12, 0),
                        'break_end' => $currentDate->copy()->setTime(13, 0),
                    ]);

                    if ($attendance->approval_status === Attendance::APPROVAL_PENDING) {
                        $attendanceCorrectRequest = AttendanceCorrectRequest::create([
                            'attendance_id' => $attendance->id,
                            'clock_in' => $attendance->clock_in,
                            'clock_out' => $currentDate->copy()->setTime(20, 0),
                            'remarks' => '遅延のため',
                            'status' => AttendanceCorrectRequest::STATUS_PENDING,
                        ]);

                        BreakCorrectRequest::create([
                            'attendance_correct_request_id' => $attendanceCorrectRequest->id,
                            'break_start' => $currentDate->copy()->setTime(12, 0),
                            'break_end' => $currentDate->copy()->setTime(14, 0),
                        ]);
                    }

                    elseif ($attendance->approval_status === Attendance::APPROVAL_APPROVED) {
                        $attendanceCorrectRequest = AttendanceCorrectRequest::create([
                            'attendance_id' => $attendance->id,
                            'clock_in' => $attendance->clock_in,
                            'clock_out' => $attendance->clock_out,
                            'remarks' => '遅延のため',
                            'status' => AttendanceCorrectRequest::STATUS_APPROVED,
                        ]);

                        BreakCorrectRequest::create([
                            'attendance_correct_request_id' => $attendanceCorrectRequest->id,
                            'break_start' => $attendance->break_start,
                            'break_end' => $attendance->break_end,
                        ]);
                    }

                    $attendanceCount++;
                }
                $currentDate->addDay();
            }
        });
    }
}
