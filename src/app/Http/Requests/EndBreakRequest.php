<?php

namespace App\Http\Requests;

class EndBreakRequest extends WorkStatusRequest
{
    public function handle()
    {
        $this->attendanceService->endBreak();
    }
}
