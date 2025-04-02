<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\AttendanceCorrectRequest;
use Carbon\Carbon;

class AttendanceCorrectRequestSeeder extends Seeder
{
    public function run()
    {
        Attendance::all()->each(function ($attendance) {
            if ($attendance->approval_status === Attendance::APPROVAL_PENDING) {
                AttendanceCorrectRequest::create([
                    'attendance_id' => $attendance->id,
                    'clock_in' => $attendance->clock_in,
                    'clock_out' => Carbon::parse($attendance->date)->setTime(20, 0),
                    'remarks' => '遅延のため',
                    'status' => AttendanceCorrectRequest::STATUS_PENDING,
                ]);
            } elseif ($attendance->approval_status === Attendance::APPROVAL_APPROVED) {
                AttendanceCorrectRequest::create([
                    'attendance_id' => $attendance->id,
                    'clock_in' => $attendance->clock_in,
                    'clock_out' => $attendance->clock_out,
                    'remarks' => '遅延のため',
                    'status' => AttendanceCorrectRequest::STATUS_APPROVED,
                ]);
            }
        });
    }
}
