<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\BreakModel;
use Carbon\Carbon;

class BreakSeeder extends Seeder
{
    public function run()
    {
        Attendance::all()->each(function ($attendance) {
            BreakModel::factory()->create([
                'attendance_id' => $attendance->id,
                'break_start' => Carbon::parse($attendance->date)->setTime(12, 0),
                'break_end' => Carbon::parse($attendance->date)->setTime(13, 0),
            ]);
        });
    }
}
