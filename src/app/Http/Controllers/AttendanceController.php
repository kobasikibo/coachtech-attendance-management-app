<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceCorrectRequest;
use App\Services\AttendanceService;
use App\Services\BreakService;
use Illuminate\Http\Request;
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

    public function create()
    {
        $attendance = $this->attendanceService->getAttendanceForToday(Auth::id());
        $isAttendanceToday = $this->attendanceService->isAttendanceToday($attendance);

        return view('attendance.create', compact('attendance', 'isAttendanceToday'));
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

    public function show($id)
    {
        $attendance = Attendance::with('user', 'breaks')->findOrFail($id);

        if ($attendance->approval_status === Attendance::APPROVAL_PENDING) {
            $correctionRequest = AttendanceCorrectRequest::where('attendance_id', $attendance->id)->latest()->first();

            if ($correctionRequest) {
                return redirect()->route('stamp_correction_request.show', $correctionRequest->id);
            }
        }

        return view('attendance.show', [
            'attendance' => $attendance,
            'formattedBreaks' => $this->breakService->formatBreakSessions($attendance),
            'attendanceService' => $this->attendanceService,
        ]);
    }
}