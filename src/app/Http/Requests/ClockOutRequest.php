<?php

namespace App\Http\Requests;

class ClockOutRequest extends WorkStatusRequest
{
    public function handle()
    {
        $this->attendanceService->clockOut();
    }
}