<?php

namespace App\Services;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceService
{
    public function clockIn()
    {
        $attendance = Attendance::where('user_id', Auth::id())->whereDate('created_at', today())->first();

        if ($attendance) {
            throw new \Exception('本日はすでに出勤しています。');
        }

        return Attendance::create([
            'user_id' => Auth::id(),
            'status' => '出勤中',
            'clock_in' => now(),
        ]);
    }

    public function clockOut()
    {
        $attendance = Attendance::where('user_id', Auth::id())->whereDate('created_at', today())->first();

        if (!$attendance || $attendance->status === '退勤済') {
            throw new \Exception('退勤できません。');
        }

        $attendance->update([
            'status' => '退勤済',
            'clock_out' => now(),
        ]);
    }

    public function startBreak()
    {
        $attendance = Attendance::where('user_id', Auth::id())->whereDate('created_at', today())->first();

        if (!$attendance || $attendance->status !== '出勤中') {
            throw new \Exception('休憩開始できません。');
        }

        $attendance->update([
            'status' => '休憩中',
            'break_start' => now(),
        ]);
    }

    public function endBreak()
    {
        $attendance = Attendance::where('user_id', Auth::id())->whereDate('created_at', today())->first();

        if (!$attendance || $attendance->status !== '休憩中') {
            throw new \Exception('休憩終了できません。');
        }

        $attendance->update([
            'status' => '出勤中',
            'break_end' => now(),
        ]);
    }
}
