<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Attendance;

class ClockOutTest extends TestCase
{
    #[Test]
    public function user_can_clock_out()
    {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
        ]);

        $this->actingAs($this->user);

        // // 画面上に「退勤」ボタンが表示されていることを確認
        $response = $this->get(route('attendance.create'));
        $response->assertSee('btn-submit-clock-out');

        // 退勤処理を実行
        $response = $this->post(route('attendance.clockOut'));

        // ステータスが「退勤済」になったことを確認
        $attendance = Attendance::where('user_id', $this->user->id)
            ->where('date', today())
            ->first();

        $this->assertEquals(Attendance::STATUS_CLOCKED_OUT, $attendance->status);
    }

    #[Test]
    public function clock_out_time_is_recorded()
    {
        $this->actingAs($this->user);

        // 出勤処理を実行
        $this->post(route('attendance.clockIn'));

        // 退勤処理を実行
        $this->post(route('attendance.clockOut'));

        // 退勤時刻を確認
        $attendance = Attendance::where('user_id', $this->user->id)->first();
        $this->assertNotNull($attendance->clock_out);
    }
}

