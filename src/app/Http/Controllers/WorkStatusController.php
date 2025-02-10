<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClockInRequest;
use App\Http\Requests\ClockOutRequest;
use App\Http\Requests\StartBreakRequest;
use App\Http\Requests\EndBreakRequest;

class WorkStatusController extends Controller
{
    public function clockIn(ClockInRequest $request)
    {
        try {
            $request->handleClockIn();
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function clockOut(ClockOutRequest $request)
    {
        try {
            $request->handleClockOut();
            return redirect()->back()->with('success', 'お疲れ様でした。');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function startBreak(StartBreakRequest $request)
    {
        try {
            $request->handleStartBreak();
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function endBreak(EndBreakRequest $request)
    {
        try {
            $request->handleEndBreak();
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
