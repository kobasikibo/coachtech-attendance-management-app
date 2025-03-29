<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakModel;
use App\Services\AttendanceService;
use App\Services\BreakService;
use Illuminate\Http\Request;
use App\Http\Requests\AttendanceRequest;
use Carbon\Carbon;

class AttendanceController extends Controller
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

        return view('admin.attendance.index', [
            'attendances' => $attendances,
            'currentDay' => $currentDay,
            'attendanceService' => $this->attendanceService,
            'breakService' => $this->breakService,
        ]);
    }

    public function show($id)
    {
        $attendance = Attendance::with('user', 'breaks')->findOrFail($id);

        return view('admin.attendance.show', [
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

        return redirect()->route('admin.attendance.show', $id);
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

    public function indexStaffAttendance(Request $request, $id)
    {
        $currentMonth = Carbon::parse($request->query('month', now()->format('Y-m')));

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $previousMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

        $attendances = Attendance::where('user_id', $id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'asc')
            ->get();

        $user = User::find($id);

        $dates = $this->attendanceService->getAllDatesOfMonth($currentMonth);

        return view('admin.attendance.staff', [
            'attendances' => $attendances,
            'user' => $user,
            'currentMonth' => $currentMonth,
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth,
            'breakService' => $this->breakService,
            'attendanceService' => $this->attendanceService,
            'dates' => $dates,
        ]);
    }

    public function exportCsv($id, Request $request, AttendanceService $attendanceService, BreakService $breakService)
    {
        $month = $request->query('month', now()->format('Y-m'));

        // ユーザー情報を取得
        $user = User::findOrFail($id);

        // 該当月の勤怠データ取得
        $attendances = Attendance::where('user_id', $id)
            ->where('date', 'like', $month . '%')
            ->get();

        $safeUserName = str_replace([' ', '　'], '_', $user->name);
        $fileName = "attendance_{$safeUserName}_{$month}.csv";

        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($attendances, $attendanceService, $breakService) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM 追加

            fputcsv($handle, ['日付', '出勤', '退勤', '休憩', '合計']);

            foreach ($attendances as $attendance) {
                fputcsv($handle, [
                    Carbon::parse($attendance->date)->format('Y/m/d (D)'),
                    $attendanceService->formatClockIn($attendance),
                    $attendanceService->formatClockOut($attendance),
                    $breakService->formatBreakTime($attendance),
                    $attendanceService->formatWorkTime($attendance),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
