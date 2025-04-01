<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class StaffIndexTest extends TestCase
{
    #[Test]
    public function names_and_emails_displayed_correctly(): void
    {
        // 全ての一般ユーザーの氏名とメールアドレスが正しく表示されている
        $users = User::factory()->count(3)->create();

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.staff.index'))
            ->assertStatus(200);

        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }

    #[Test]
    public function attendance_list_displayed_correctly(): void
    {
        // ユーザーの勤怠情報が正確に表示される
        $today = Carbon::today();

        $firstDay = $today->copy()->startOfMonth();
        $secondDay = $firstDay->copy()->addDay();
        $thirdDay = $firstDay->copy()->addDays(2);

        $attendances = collect([
            $firstDay,
            $secondDay,
            $thirdDay,
        ])->map(fn($date) => Attendance::factory()
            ->forDate($date)
            ->create([
            'user_id' => $this->user->id,
        ]));

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $this->user->id]))
            ->assertStatus(200);

        foreach ($attendances as $attendance) {
            $response->assertSee($attendance->user->name);
            $response->assertSee($attendance->date->format('Y/m'));
            $response->assertSee($attendance->date->translatedFormat('m/d(D)'));
            $response->assertSee($this->attendanceService->formatClockIn($attendance));
            $response->assertSee($this->attendanceService->formatClockOut($attendance));
            $response->assertSee($this->breakService->formatBreakTime($attendance));
            $response->assertSee($this->attendanceService->formatWorkTime($attendance));
        }
    }

    #[Test]
    public function previous_month_list_displayed(): void
    {
        // 「前月」を押下した時に表示月の前月の情報が表示される
        $randomDate = Carbon::now()->subMonth()->addDays(rand(1, Carbon::now()->subMonth()->daysInMonth));

        $attendance = Attendance::factory()
            ->forDate($randomDate)
            ->create([
                'user_id' => $this->user->id,
            ]);

        $previousMonth = Carbon::now()->subMonth()->format('Y-m');

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $this->user->id]))
            ->assertStatus(200);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $this->user->id, 'month' => $previousMonth]))
            ->assertStatus(200);

        $response->assertSee($previousMonth);
        $response->assertSee($this->attendanceService->formatClockIn($attendance));
    }

    #[Test]
    public function next_month_list_displayed(): void
    {
        // 「翌月」を押下した時に表示月の翌月の情報が表示される
        $randomDate = Carbon::now()->addMonth()->addDays(rand(1, Carbon::now()->addMonth()->daysInMonth));

        $attendance = Attendance::factory()
            ->forDate($randomDate)
            ->create([
                'user_id' => $this->user->id,
            ]);

        $nextMonth = Carbon::now()->addMonth()->format('Y-m');

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $this->user->id]))
            ->assertStatus(200);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $this->user->id, 'month' => $nextMonth]))
            ->assertStatus(200);

        $response->assertSee($nextMonth);
        $response->assertSee($this->attendanceService->formatClockIn($attendance));
    }

    #[Test]
    public function clicking_detail_to_attendance_detail(): void
    {
        // その日の勤怠詳細画面に遷移する
        $date = Carbon::now();

        $attendance = Attendance::factory()
            ->forDate($date)
            ->create(['user_id' => $this->user->id,]);

        $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $this->user->id]))
            ->assertStatus(200);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.show', $attendance->id));

        $response->assertSee($attendance->date->format('n月j日'));
    }
}
