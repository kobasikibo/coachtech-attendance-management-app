<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\AttendanceCorrectRequest;
use App\Models\BreakCorrectRequest;
use App\Services\AttendanceService;
use App\Services\AttendanceCorrectRequestService;
use App\Services\BreakCorrectRequestService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StampCorrectionRequestController extends Controller
{
    protected $attendanceService;
    protected $attendanceCorrectService;
    protected $breakCorrectService;

    public function __construct(AttendanceService $attendanceService, BreakCorrectRequestService $breakCorrectService, AttendanceCorrectRequestService $attendanceCorrectService)
    {
        $this->attendanceService = $attendanceService;
        $this->breakCorrectService = $breakCorrectService;
        $this->attendanceCorrectService = $attendanceCorrectService;
    }

    public function requestCorrection(AttendanceRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $date = $attendance->date;

        $correction = AttendanceCorrectRequest::create([
            'attendance_id' => $attendance->id,
            'clock_in' => Carbon::parse("$date {$request->clock_in}"),
            'clock_out' => Carbon::parse("$date {$request->clock_out}"),
            'remarks' => $request->remarks,
            'status' => AttendanceCorrectRequest::STATUS_PENDING,
        ]);

        if (!empty($request->breaks)) {
            foreach ($request->breaks as $key => $breakData) {
                BreakCorrectRequest::create([
                    'attendance_correct_request_id' => $correction->id,
                    'break_id' => $key ?: null, // 既存の休憩がない場合 null
                    'break_start' => Carbon::parse("$date {$breakData['break_start']}"),
                    'break_end' => Carbon::parse("$date {$breakData['break_end']}"),
                ]);
            }
        }

        $attendance->update([
            'approval_status' => Attendance::APPROVAL_PENDING,
        ]);

        return redirect()->route('stamp_correction_request.show', $correction->id);
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        if (!in_array($tab, ['pending', 'approved'])) {
            $tab = 'pending';
        }

        $userId = Auth::id();
        $correctionRequests = $this->attendanceCorrectService->getCorrectionRequestsByTab($userId, $tab);

        return view('stamp_correction_request.index', [
            'correctionRequests' => $correctionRequests,
            'tab' => $tab,
        ]);
    }

    public function show(AttendanceCorrectRequest $attendanceCorrectRequest)
    {
        $attendance = $attendanceCorrectRequest->attendance;
        $correctedBreaks = $this->breakCorrectService->formatBreakSessions($attendanceCorrectRequest);

        return view('stamp_correction_request.show', [
            'attendance' => $attendance,
            'attendanceService' => $this->attendanceService,
            'correctionRequest' => $attendanceCorrectRequest,
            'formattedBreaks' => $correctedBreaks,
        ]);
    }
}
