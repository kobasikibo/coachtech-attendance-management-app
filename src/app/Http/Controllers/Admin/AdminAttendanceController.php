<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // 現在の日付を取得
        $currentDay = $request->query('clock_in', now()->format('Y-m-d'));

        // 管理者は全ユーザーの勤怠情報を取得する
        $attendances = Attendance::with('user') // ユーザー情報も取得
        ->whereDate('clock_in', $currentDay) // clock_inの日付が一致する勤怠情報を取得
            ->orderBy('user_id') // ユーザーIDでソート（必要に応じて）
            ->get();

        return view('admin.attendance-index', compact('attendances', 'currentDay'));
    }

    public function show($attendanceId)
    {
        $attendance = Attendance::with('user')
            ->findOrFail($attendanceId);

        return view('admin.attendance.show', compact('attendance'));
    }
}
