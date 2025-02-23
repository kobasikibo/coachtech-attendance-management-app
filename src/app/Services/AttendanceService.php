<?php

namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;
use App\Services\BreakService;

class AttendanceService
{
    protected $breakService;

    public function __construct(BreakService $breakService)
    {
        $this->breakService = $breakService;
    }

    public function formatDate(Attendance $attendance)
    {
        return Carbon::parse($attendance->date)->translatedFormat('m/d(D)');
    }

    public function formatClockIn(Attendance $attendance)
    {
        return $attendance->clock_in ? Carbon::parse($attendance->clock_in)->format('H:i') : '';
    }

    public function formatClockOut(Attendance $attendance)
    {
        return $attendance->clock_out ? Carbon::parse($attendance->clock_out)->format('H:i') : '';
    }

    public function formatWorkTime(Attendance $attendance)
    {
        if ($attendance->clock_in && $attendance->clock_out) {
            $workMinutes = Carbon::parse($attendance->clock_in)->diffInMinutes(Carbon::parse($attendance->clock_out));
            $actualWorkMinutes = $workMinutes - $this->breakService->calculateTotalBreakTime($attendance);

            $workHours = floor($actualWorkMinutes / 60);
            $workMinutes = $actualWorkMinutes % 60;

            return intval($workHours) . ':' . str_pad(intval($workMinutes), 2, '0', STR_PAD_LEFT);
        }
        return '';
    }

    public function getYearFromClockIn(Attendance $attendance)
    {
        return Carbon::parse($attendance->clock_in)->format('Y年');
    }

    public function getMonthDayFromClockIn(Attendance $attendance)
    {
        return Carbon::parse($attendance->clock_in)->format('n月j日');
    }

    public function getAttendanceForToday($userId)
    {
        return Attendance::forToday()->byUser($userId)->first();
    }

    public function isAttendanceToday($attendance)
    {
        return $attendance && Carbon::parse($attendance->date)->isToday();
    }

    public function getAllDatesOfMonth($month)
    {
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $dates = [];
        for ($date = $startOfMonth; $date <= $endOfMonth; $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
}
