<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceCorrectRequest;
use App\Models\BreakModel;
use App\Models\BreakCorrectRequest;
use Carbon\Carbon;

class BreakCorrectRequestSeeder extends Seeder
{
    public function run()
    {
        AttendanceCorrectRequest::all()->each(function ($request) {
            $break = BreakModel::where('attendance_id', $request->attendance_id)->first();

            if ($break) {
                BreakCorrectRequest::create([
                    'attendance_correct_request_id' => $request->id,
                    'break_id' => $break->id,
                    'break_start' => ($request->status === AttendanceCorrectRequest::STATUS_PENDING)
                        ? Carbon::parse($break->break_start)->setTime(12, 0)
                        : $break->break_start,
                    'break_end' => ($request->status === AttendanceCorrectRequest::STATUS_PENDING)
                        ? Carbon::parse($break->break_end)->setTime(14, 0)
                        : $break->break_end,
                ]);
            }
        });
    }
}
