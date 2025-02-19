<?php

namespace App\Http\Requests;

class StartBreakRequest extends WorkStatusRequest
{
    public function handle()
    {
        $this->attendanceService->startBreak();
    }
}
