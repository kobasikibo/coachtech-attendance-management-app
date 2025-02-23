<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\BreakModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkStatusService
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function getStatus($attendance)
    {
        return $attendance ? $attendance->status : Attendance::STATUS_OFF_DUTY;
    }

    public function clockIn()
    {
        $attendance = $this->attendanceService->getAttendanceForToday(Auth::id());

        if ($attendance) {
            throw new \Exception('本日はすでに出勤しています。');
        }

        return Attendance::create([
            'user_id' => Auth::id(),
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
            'clock_in' => now(),
        ]);
    }

    public function clockOut()
    {
        $attendance = $this->attendanceService->getAttendanceForToday(Auth::id());

        if (!$attendance || $attendance->status === Attendance::STATUS_CLOCKED_OUT) {
            throw new \Exception('退勤できません。');
        }

        $attendance->update([
            'status' => Attendance::STATUS_CLOCKED_OUT,
            'clock_out' => now(),
        ]);
    }

    public function startBreak()
    {
        $attendance = $this->attendanceService->getAttendanceForToday(Auth::id());

        if (!$attendance || $attendance->status !== Attendance::STATUS_CLOCKED_IN) {
            throw new \Exception('休憩開始できません。');
        }

        BreakModel::create([
            'attendance_id' => $attendance->id,
            'break_start' => now(),
        ]);

        $attendance->update([
            'status' => Attendance::STATUS_ON_BREAK,
        ]);
    }

    public function endBreak()
    {
        $attendance = $this->attendanceService->getAttendanceForToday(Auth::id());

        if (!$attendance || $attendance->status !== Attendance::STATUS_ON_BREAK) {
            throw new \Exception('休憩終了できません。');
        }

        $lastBreak = BreakModel::where('attendance_id', $attendance->id)->latest()->first();

        if (!$lastBreak || $lastBreak->break_end) {
            throw new \Exception('休憩開始が記録されていません。');
        }

        $lastBreak->update([
            'break_end' => now(),
        ]);

        $attendance->update([
            'status' => Attendance::STATUS_CLOCKED_IN,
        ]);
    }
}