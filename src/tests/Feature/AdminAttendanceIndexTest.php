<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Admin;
use App\Models\User;
use App\Models\Attendance;
use App\Services\AttendanceService;
use App\Services\BreakService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AdminAttendanceIndexTest extends TestCase
{
    use RefreshDatabase;

    private Admin $adminUser;

    protected $attendanceService;
    protected $breakService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attendanceService = app(AttendanceService::class);
        $this->breakService = app(BreakService::class);

        // テスト用の管理者ユーザーを作成
        $this->adminUser = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpass'),
            'email_verified_at' => now(),
        ]);
    }

    #[Test]
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
    public function it_displays_current_date_on_attendance_list(): void
    {
        // 遷移した際に現在の日付が表示される
        $date = Carbon::today()->format('Y/m/d');

        $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.index'))
            ->assertStatus(200)
            ->assertSee($date);
    }

    #[Test]
    public function it_displays_previous_day_attendance(): void
    {
        // 「前日」を押下した時に前の日の勤怠情報が表示される
        $date = Carbon::yesterday()->format('Y/m/d');
        $user = User::factory()->create();
        Attendance::factory()->create(['user_id' => $user->id, 'date' => $date]);

        $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.index', ['date' => $date]))
            ->assertStatus(200)
            ->assertSee($user->name);
    }

    #[Test]
    public function it_displays_next_day_attendance(): void
    {
        // 「翌日」を押下した時に次の日の勤怠情報が表示される
        $date = Carbon::tomorrow()->format('Y/m/d');
        $user = User::factory()->create();
        Attendance::factory()->create(['user_id' => $user->id, 'date' => $date]);

        $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.index', ['date' => $date]))
            ->assertStatus(200)
            ->assertSee($user->name);
    }
}
