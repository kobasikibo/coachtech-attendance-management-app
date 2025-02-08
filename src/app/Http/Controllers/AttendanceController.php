<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Http\Requests\ClockInRequest;
use App\Http\Requests\StartBreakRequest;
use App\Http\Requests\EndBreakRequest;
use App\Http\Requests\ClockOutRequest;

class AttendanceController extends Controller
{
    public function show()
    {
        $attendance = Attendance::where('user_id', auth()->id())->latest()->first();
        return view('attendance.show', compact('attendance'));
    }

    public function clockIn(ClockInRequest $request)
    {
        Attendance::create($request->validateClockIn());

        return redirect()->back();
    }

    public function startBreak(StartBreakRequest $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('created_at', today())->first();
        $attendance->update($request->validateStartBreak());

        return redirect()->back();
    }

    public function endBreak(EndBreakRequest $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('created_at', today())->first();
        $attendance->update($request->validateEndBreak());

        return redirect()->back();
    }

    public function clockOut(ClockOutRequest $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('created_at', today())->first();
        $attendance->update($request->validateClockOut());

        return redirect()->back()->with('success', 'お疲れ様でした。');
    }
}