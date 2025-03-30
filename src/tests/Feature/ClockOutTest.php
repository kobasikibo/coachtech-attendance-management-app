<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ClockOutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_clock_out()
    {
        $user = User::factory()->create();
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
        ]);

        /** @var Authenticatable $user */
        $this->actingAs($user);

        // // 画面上に「退勤」ボタンが表示されていることを確認
        $response = $this->get(route('attendance.create'));
        $response->assertSee('btn-submit-clock-out');

        // 退勤処理を実行
        $response = $this->post(route('attendance.clockOut'));

        // ステータスが「退勤済」になったことを確認
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', today())
            ->first();

        $this->assertEquals(Attendance::STATUS_CLOCKED_OUT, $attendance->status);
    }

    #[Test]
    public function clock_out_time_is_recorded()
    {
        $user = User::factory()->create();

        /** @var Authenticatable $user */
        $this->actingAs($user);

        // 出勤処理を実行
        $this->post(route('attendance.clockIn'));

        // 退勤処理を実行
        $this->post(route('attendance.clockOut'));

        // 勤時刻を確認
        $attendance = Attendance::where('user_id', $user->id)->first();
        $this->assertNotNull($attendance->clock_out);
    }
}

