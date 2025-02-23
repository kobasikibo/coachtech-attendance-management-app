<?php

namespace App\Services;

use App\Models\Attendance;

class BreakService
{
    /**
     * 総休憩時間を計算（分単位）
     */
    public function calculateTotalBreakTime(Attendance $attendance)
    {
        return $attendance->breaks->sum(function ($break) {
            return $break->break_start && $break->break_end
                ? $break->break_start->diffInMinutes($break->break_end)
                : 0;
        });
    }

    /**
     * 休憩時間のフォーマット
     */
    public function formatBreakTime(Attendance $attendance)
    {
        $breakMinutes = $this->calculateTotalBreakTime($attendance);
        $breakHours = floor($breakMinutes / 60);
        $breakMinutes = $breakMinutes % 60;

        return intval($breakHours) . ':' . str_pad(intval($breakMinutes), 2, '0', STR_PAD_LEFT);
    }

    /**
     * 休憩データのフォーマット
     */
    public function formatBreakSessions(Attendance $attendance)
    {
        return $attendance->breaks->map(function ($break) {
            return [
                'id' => $break->id,
                'break_start' => $break->break_start ? $break->break_start->format('H:i') : '',
                'break_end' => $break->break_end ? $break->break_end->format('H:i') : '',
            ];
        })->toArray();
    }
}
