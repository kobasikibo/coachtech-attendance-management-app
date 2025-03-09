<?php

namespace App\Services;

use App\Models\AttendanceCorrectRequest;

class BreakCorrectRequestService
{
    public function formatBreakSessions(AttendanceCorrectRequest $attendanceCorrectRequest)
    {
        return $attendanceCorrectRequest->breakCorrectRequests->map(function ($break) {
            return [
                'id' => $break->id,
                'break_start' => $break->break_start ? $break->break_start->format('H:i') : '',
                'break_end' => $break->break_end ? $break->break_end->format('H:i') : '',
            ];
        })->toArray();
    }
}