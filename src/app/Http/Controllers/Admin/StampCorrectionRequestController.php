<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Services\AttendanceService;
use App\Services\BreakService;

class StampCorrectionRequestController extends Controller
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
        $tab = $request->query('tab', 'pending');

        if (!in_array($tab, ['pending', 'approved'])) {
            $tab = 'pending';
        }

        if ($tab === 'pending') {
            $attendances = Attendance::where('approval_status', Attendance::APPROVAL_PENDING)->get();
        } elseif ($tab === 'approved') {
            $attendances = Attendance::where('approval_status', Attendance::APPROVAL_APPROVED)->get();
        }

        return view('admin.stamp_correction_request.index', [
            'attendances' => $attendances,
            'tab' => $tab,
        ]);
    }

    public function show(AttendanceCorrectRequest $attendanceCorrectRequest)
    {
        $attendance = Attendance::with('user', 'breaks')->findOrFail($attendanceCorrectRequest->attendance_id);

        return view('admin.stamp_correction_request.show', [
            'attendance' => $attendance,
            'formattedBreaks' => $this->breakService->formatBreakSessions($attendance),
            'attendanceService' => $this->attendanceService,
        ]);
    }
}
