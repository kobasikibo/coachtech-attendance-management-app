<?php

namespace App\Helpers;

use Carbon\Carbon;

class AttendanceHelper
{
    public static function formatBreakTimes($breaks)
    {
        return $breaks->map(function ($break) {
            return [
                'id' => $break->id,
                'break_start' => $break->break_start ? Carbon::parse($break->break_start)->format('H:i') : '',
                'break_end' => $break->break_end ? Carbon::parse($break->break_end)->format('H:i') : '',
            ];
        });
    }

    public static function calculateBreakTime($breaks)
    {
        return $breaks->sum(function ($break) {
            return $break->break_start && $break->break_end
                ? Carbon::parse($break->break_start)->diffInMinutes(Carbon::parse($break->break_end))
                : 0;
        });
    }

    public static function formatWorkTime($clockIn, $clockOut, $breakMinutes)
    {
        if (!$clockIn || !$clockOut) return '-';

        $workMinutes = Carbon::parse($clockIn)->diffInMinutes(Carbon::parse($clockOut)) - $breakMinutes;
        return floor($workMinutes / 60) . ':' . str_pad($workMinutes % 60, 2, '0', STR_PAD_LEFT);
    }
}