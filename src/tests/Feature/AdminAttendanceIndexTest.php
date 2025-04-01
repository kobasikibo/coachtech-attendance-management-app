<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminAttendanceIndexTest extends TestCase
{
    public function it_displays_attendance_list_correctly(): void
    {
        // その日になされた全ユーザーの勤怠情報が正確に確認できる
        $date = Carbon::today()->format('Y/m/d');
        $users = User::factory()->count(3)->create();
        $attendances = $users->map(fn($user) => Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
        ]));

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.index', ['date' => $date]))
            ->assertStatus(200);

        foreach ($attendances as $attendance) {
            $response->assertSee($attendance->user->name);
            $response->assertSee($attendance->date->format('Y/m/d'));
            $response->assertSee($this->attendanceService->formatClockIn($attendance));
            $response->assertSee($this->attendanceService->formatClockOut($attendance));
            $response->assertSee($this->breakService->formatBreakTime($attendance));
            $response->assertSee($this->attendanceService->formatWorkTime($attendance));
        }

        foreach ($users as $user) {
            $response->assertSee($user->name);
        }
    }

    #[Test]
    public function current_date_is_displayed_on_attendance_list(): void
    {
        // 遷移した際に現在の日付が表示される
        $date = Carbon::today()->format('Y/m/d');

        $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.index'))
            ->assertStatus(200)
            ->assertSee($date);
    }

    #[Test]
    public function previous_day_attendance_is_displayed(): void
    {
        // 「前日」を押下した時に前の日の勤怠情報が表示される
        $date = Carbon::yesterday()->format('Y/m/d');
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id, 'date' => $date]);

        $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.index', ['date' => $date]))
            ->assertStatus(200)
            ->assertSee(Carbon::parse($attendance->date)->format('Y年n月j日の勤怠'))
            ->assertSee($this->attendanceService->formatClockIn($attendance));
    }

    #[Test]
    public function next_day_attendance_is_displayed(): void
    {
        // 「翌日」を押下した時に次の日の勤怠情報が表示される
        $date = Carbon::tomorrow()->format('Y/m/d');
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id, 'date' => $date]);

        $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.index', ['date' => $date]))
            ->assertStatus(200)
            ->assertSee(Carbon::parse($attendance->date)->format('Y年n月j日の勤怠'))
            ->assertSee($this->attendanceService->formatClockIn($attendance));
    }
}
