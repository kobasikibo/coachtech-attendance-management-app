<?php

namespace App\Http\Requests;

class ClockInRequest extends WorkStatusRequest
{
    public function handle()
    {
        $this->attendanceService->clockIn();
    }
}