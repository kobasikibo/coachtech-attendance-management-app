<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakModel;
use App\Models\AttendanceCorrectRequest;
use App\Models\BreakCorrectRequest;
use App\Services\AttendanceService;
use App\Services\AttendanceCorrectRequestService;
use App\Services\BreakCorrectRequestService;

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

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        if (!in_array($tab, ['pending', 'approved'])) {
            $tab = 'pending';
        }

        if ($tab === 'pending') {
            $correctionRequests = AttendanceCorrectRequest::where('status', AttendanceCorrectRequest::STATUS_PENDING)->get();
        } elseif ($tab === 'approved') {
            $correctionRequests = AttendanceCorrectRequest::where('status', AttendanceCorrectRequest::STATUS_APPROVED)->get();
        }

        return view('admin.stamp_correction_request.index', [
            'correctionRequests' => $correctionRequests,
            'tab' => $tab,
        ]);
    }

    public function show(AttendanceCorrectRequest $attendanceCorrectRequest)
    {
        $attendance = $attendanceCorrectRequest->attendance;
        $correctedBreaks = $this->breakCorrectService->formatBreakSessions($attendanceCorrectRequest);

        return view('admin.stamp_correction_request.show', [
            'attendance' => $attendance,
            'attendanceService' => $this->attendanceService,
            'correctionRequest' => $attendanceCorrectRequest,
            'formattedBreaks' => $correctedBreaks,
        ]);
    }

    public function approve(AttendanceCorrectRequest $attendanceCorrectRequest)
    {
        $attendance = Attendance::findOrFail($attendanceCorrectRequest->attendance_id);

        // 勤怠情報を更新
        $attendance->update([
            'clock_in' => $attendanceCorrectRequest->clock_in,
            'clock_out' => $attendanceCorrectRequest->clock_out,
            'remarks' => $attendanceCorrectRequest->remarks,
            'approval_status' => Attendance::APPROVAL_APPROVED,
        ]);

        // 休憩情報を更新
        $breakCorrections = BreakCorrectRequest::where('attendance_correct_request_id', $attendanceCorrectRequest->id)->get();
        foreach ($breakCorrections as $breakCorrection) {
            if ($breakCorrection->break_id) {
                // 既存の休憩を更新
                $break = BreakModel::find($breakCorrection->break_id);
                if ($break) {
                    $break->update([
                        'break_start' => $breakCorrection->break_start,
                        'break_end' => $breakCorrection->break_end,
                    ]);
                }
            } else {
                // 新規の休憩を作成
                BreakModel::create([
                    'attendance_id' => $attendance->id,
                    'break_start' => $breakCorrection->break_start,
                    'break_end' => $breakCorrection->break_end,
                ]);
            }
        }

        $attendanceCorrectRequest->update(['status' => AttendanceCorrectRequest::STATUS_APPROVED]);

        return redirect()->route('admin.stamp_correction_request.show', [
            'attendance_correct_request' => $attendanceCorrectRequest->id
        ]);
    }
}
