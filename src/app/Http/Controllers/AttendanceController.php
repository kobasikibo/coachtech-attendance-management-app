<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function show()
    {
        $attendance = Attendance::where('user_id', auth()->id())->latest()->first();
        return view('attendance.show', compact('attendance'));
    }

    public function index(Request $request)
    {
        // 現在の年月を取得
        $currentMonth = $request->query('month', now()->format('Y-m'));

        // 選択された月の勤怠情報を取得
        $attendances = Attendance::where('user_id', auth()->id())
            ->whereYear('clock_in', substr($currentMonth, 0, 4))
            ->whereMonth('clock_in', substr($currentMonth, 5, 2))
            ->orderBy('clock_in', 'asc')
            ->get();

        return view('attendance.index', compact('attendances', 'currentMonth'));
    }

    public function detail($id)
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        return view('attendance.detail', compact('attendance'));
    }
}
