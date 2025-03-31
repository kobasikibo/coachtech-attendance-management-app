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

class StaffIndexTest extends TestCase
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
        $user = User::factory()->create();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $dayBeforeYesterday = Carbon::today()->subDays(2);
        $attendances = collect([
            $today,
            $yesterday,
            $dayBeforeYesterday,
        ])->map(fn($date) => Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
        ]));

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $user->id]))
            ->assertStatus(200);

        foreach ($attendances as $attendance) {
            $response->assertSee($attendance->user->name);
            $response->assertSee($attendance->user->date);
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
        $user = User::factory()->create();
        $randomDate = Carbon::now()->subMonth()->addDays(rand(1, Carbon::now()->subMonth()->daysInMonth));
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => $randomDate,
        ]);
        $previousMonth = Carbon::now()->subMonth()->format('Y-m');

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $user->id]))
            ->assertStatus(200);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $user->id], ['month' => $previousMonth]))
            ->assertStatus(200);

        $response->assertSee($previousMonth);
        $response->assertSee($this->attendanceService->formatClockIn($attendance));
    }

    #[Test]
    public function next_month_list_displayed(): void
    {
        // 「翌月」を押下した時に表示月の翌月の情報が表示される
        $user = User::factory()->create();
        $randomDate = Carbon::now()->addMonth()->addDays(rand(1, Carbon::now()->addMonth()->daysInMonth));
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => $randomDate,
        ]);
        $nextMonth = Carbon::now()->addMonth()->format('Y-m');

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.staff', ['id' => $user->id]))
            ->assertStatus(200);

        $response = $this->actingAs($this->adminUser, 'admin')
        ->get(route('admin.attendance.staff', ['id' => $user->id, 'month' => $nextMonth]))
            ->assertStatus(200);

        $response->assertSee($nextMonth);
        $response->assertSee($this->attendanceService->formatClockIn($attendance));
    }

    #[Test]
    public function clicking_detail_to_attendance_detail(): void
    {
        // その日の勤怠詳細画面に遷移する
        $user = User::factory()->create();
        $date = Carbon::now();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
        ]);

        $this->actingAs($this->adminUser, 'admin')
        ->get(route('admin.attendance.staff', ['id' => $user->id]))
        ->assertStatus(200);

        $response = $this->actingAs($this->adminUser, 'admin')
        ->get(route('admin.attendance.show', $attendance->id));

        $response->assertSee(Carbon::parse($attendance->date)->format('n月j日'));
    }
}
