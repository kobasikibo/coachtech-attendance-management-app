<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BreakModel;
use App\Services\AttendanceService;
use App\Services\BreakService;
use Illuminate\Http\Request;
use App\Http\Requests\AttendanceRequest;
use Carbon\Carbon;

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

    public function detail($id)
    {
        $attendance = Attendance::with('user', 'breaks')->findOrFail($id);

        return view('admin.attendance-detail', [
            'attendance' => $attendance,
            'formattedBreaks' => $this->breakService->formatBreakSessions($attendance),
            'attendanceService' => $this->attendanceService,
        ]);
    }

    public function update(AttendanceRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $date = $attendance->date;

        // 勤怠情報を更新
        $this->updateAttendance($attendance, $date, $request);

        // 休憩情報を更新
        $this->updateBreaks($attendance, $request);

        return redirect()->route('admin.attendance.detail', $id);
    }

    private function updateAttendance($attendance, $date, $request)
    {
        $attendance->update([
            'clock_in' => Carbon::parse("$date {$request->clock_in}"),
            'clock_out' => Carbon::parse("$date {$request->clock_out}"),
            'remarks' => $request->remarks,
        ]);
    }

    private function updateBreaks($attendance, $request)
    {
        foreach ($request->breaks as $key => $breakData) {
            $break = BreakModel::where('attendance_id', $attendance->id)
                ->where('id', $key)
                ->first();

            if ($break) {
                $breakStartTime = $attendance->date . ' ' . $breakData['break_start'];
                $breakEndTime = $attendance->date . ' ' . $breakData['break_end'];

                $breakStart = Carbon::parse($breakStartTime);
                $breakEnd = Carbon::parse($breakEndTime);

                $break->update([
                    'break_start' => $breakStart,
                    'break_end'   => $breakEnd,
                ]);
            } else {
                BreakModel::create([
                    'attendance_id' => $attendance->id,
                    'break_start'    => Carbon::parse($attendance->date . ' ' . $breakData['break_start']),
                    'break_end'      => Carbon::parse($attendance->date . ' ' . $breakData['break_end']),
                ]);
            }
        }
    }
}
