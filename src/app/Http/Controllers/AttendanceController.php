<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BreakModel;
use App\Services\AttendanceService;
use App\Services\BreakService;
use Illuminate\Http\Request;
use App\Http\Requests\AttendanceRequest;
use Illuminate\Support\Facades\Auth;
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

    public function show()
    {
        $attendance = $this->attendanceService->getAttendanceForToday(Auth::id());
        $isAttendanceToday = $this->attendanceService->isAttendanceToday($attendance);

        return view('attendance.show', compact('attendance', 'isAttendanceToday'));
    }

    public function index(Request $request)
    {
        $currentMonth = Carbon::parse($request->query('month', now()->format('Y-m')));

        $startOfMonth = Carbon::parse($currentMonth)->startOfMonth();
        $endOfMonth = Carbon::parse($currentMonth)->endOfMonth();

        $previousMonth = Carbon::parse($currentMonth)->subMonth()->format('Y-m');
        $nextMonth = Carbon::parse($currentMonth)->addMonth()->format('Y-m');

        $attendances = Attendance::where('user_id', Auth::id())
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'asc')
            ->get();

        $dates = $this->attendanceService->getAllDatesOfMonth($currentMonth);

        return view('attendance.index', [
            'attendances' => $attendances,
            'currentMonth' => $currentMonth,
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth,
            'breakService' => $this->breakService,
            'attendanceService' => $this->attendanceService,
            'dates' => $dates,
        ]);
    }

    public function detail($id)
    {
        $attendance = Attendance::with('user', 'breaks')->findOrFail($id);

        return view('attendance.detail', [
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

        return redirect()->route('attendance.detail', $id);
    }

    private function updateAttendance($attendance, $date, $request)
    {
        $attendance->update([
            'clock_in' => Carbon::parse("$date {$request->clock_in}"),
            'clock_out' => Carbon::parse("$date {$request->clock_out}"),
            'remarks' => $request->remarks,
            'approval_status' => Attendance::APPROVAL_PENDING,
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