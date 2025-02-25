<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Services\AttendanceService;
use App\Services\BreakService;

class AdminAttendanceController extends Controller
{
    protected $attendanceService;
    protected $breakService;

    public function __construct(AttendanceService $attendanceService, BreakService $breakService)
    {
        $this->attendanceService = $attendanceService;
        $this->breakService = $breakService;
    }

    public function index(Request $request)
    {
        // 現在の日付を取得
        $currentDay = $request->query('clock_in', now()->format('Y-m-d'));

        // 管理者は全ユーザーの勤怠情報を取得する
        $attendances = Attendance::with('user')
        ->whereDate('clock_in', $currentDay)
            ->orderBy('user_id')
            ->get();

        return view('admin.attendance-index', [
            'attendances' => $attendances,
            'currentDay' => $currentDay,
            'attendanceService' => $this->attendanceService,
            'breakService' => $this->breakService,
        ]);
    }

    public function show($attendanceId)
    {
        $attendance = Attendance::with('user')
            ->findOrFail($attendanceId);

        return view('admin.attendance.detail', compact('attendance'));
    }
}
