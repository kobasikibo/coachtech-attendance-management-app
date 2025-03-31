<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Admin;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    private Admin $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // テスト用の管理者ユーザーを作成
        $this->adminUser = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpass'),
            'email_verified_at' => now(),
        ]);
    }

    // 勤怠詳細画面に表示されるデータが選択したものになっている
    #[Test]
    public function it_displays_correct_attendance_details(): void
    {
        // サンプルの勤怠データを作成
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'remarks' => '選択したデータ',
        ]);

        $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.attendance.show', $attendance->id))
            ->assertStatus(200)
            ->assertSee($attendance->user->name)
            ->assertSee($attendance->clock_in->format('H:i'))
            ->assertSee($attendance->clock_out->format('H:i'))
            ->assertSee($attendance->remarks);
    }

    // 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
    #[Test]
    public function it_shows_error_when_clock_in_is_after_clock_out(): void
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($this->adminUser, 'admin')
            ->put(route('admin.attendance.update', $attendance->id), [
                'clock_in' => '18:00',
                'clock_out' => '09:00',
                'remarks' => '出勤時間が退勤時間より後のデータ',
            ])
            ->assertSessionHasErrors(['clock_in' => '出勤時間もしくは退勤時間が不適切な値です']);
    }

    // 休憩開始時間が退勤時間より後になっている場合、エラーメッセージが表示される
    #[Test]
    public function it_shows_error_when_break_start_is_after_clock_out(): void
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($this->adminUser, 'admin')
            ->put(route('admin.attendance.update', $attendance->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'breaks' => [
                    [
                        'break_start' => '18:01',
                        'break_end' => '18:02'
                    ]
                ],
                'remarks' => '休憩開始時間が退勤時間より後になっているデータ',
                ])
            ->assertSessionHasErrors(['breaks.0.break_end' => '休憩時間が勤務時間外です']);
    }

    // 休憩終了時間が退勤時間より後になっている場合、エラーメッセージが表示される
    #[Test]
    public function it_shows_error_when_break_end_is_after_clock_out(): void
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($this->adminUser, 'admin')
            ->put(route('admin.attendance.update', $attendance->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'breaks' => [
                    [
                        'break_start' => '17:30',
                        'break_end' => '18:30'
                    ]
                ],
                'remarks' => '休憩終了時間が退勤時間より後になっているデータ',
                ])
            ->assertSessionHasErrors(['breaks.0.break_end' => '休憩時間が勤務時間外です']);
    }

    // 備考欄が未入力の場合のエラーメッセージが表示される
    #[Test]
    public function it_shows_error_when_remarks_is_empty(): void
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($this->adminUser, 'admin')
            ->put(route('admin.attendance.update', $attendance->id))
            ->assertSessionHasErrors(['remarks' => '備考を記入してください']);
    }
}
