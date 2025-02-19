<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakModel;
use App\Http\Requests\AttendanceRequest;
use Carbon\Carbon;

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
        $attendance = Attendance::findOrFail($id);

        $formattedBreaks = $attendance->getFormattedBreakTimes();

        return view('attendance.detail', compact('attendance', 'formattedBreaks'));
    }

    public function update(AttendanceRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $date = $request->date;

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
                $breakStartTime = $attendance->clock_in->toDateString() . ' ' . $breakData['break_start'];
                $breakEndTime = $attendance->clock_in->toDateString() . ' ' . $breakData['break_end'];

                $breakStart = Carbon::parse($breakStartTime);
                $breakEnd = Carbon::parse($breakEndTime);

                $break->update([
                    'break_start' => $breakStart,
                    'break_end'   => $breakEnd,
                ]);
            } else {
                BreakModel::create([
                    'attendance_id' => $attendance->id,
                    'break_start'    => Carbon::parse($attendance->clock_in->toDateString() . ' ' . $breakData['break_start']),
                    'break_end'      => Carbon::parse($attendance->clock_in->toDateString() . ' ' . $breakData['break_end']),
                ]);
            }
        }
    }
}