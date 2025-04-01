<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Attendance;

class ClockInTest extends TestCase
{
    #[Test]
    public function user_can_clock_in()
    {
        $this->actingAs($this->user);

        // 出勤ボタンが表示される
        $response = $this->get(route('attendance.create'));
        $response->assertSee('btn-submit-clock-in');

        // 出勤処理を実行
        $response = $this->post(route('attendance.clockIn'));

        // ステータスが「出勤中」になったことを確認
        $attendance = Attendance::where('user_id', $this->user->id)
        ->where('date', today())
        ->first();

        $this->assertEquals(Attendance::STATUS_CLOCKED_IN, $attendance->status);
    }

    #[Test]
    public function user_cannot_clock_in_more_than_once_per_day()
    {
        $this->actingAs($this->user);

        // 出勤処理を実行
        $response = $this->post(route('attendance.clockIn'));

        // 画面上に「出勤」ボタンが表示されない
        $response = $this->get(route('attendance.create'));
        $response->assertDontSee('btn-submit-clock-in');
    }

    #[Test]
    public function clock_in_time_is_recorded()
    {
        $this->actingAs($this->user);

        // 出勤処理を実行
        $this->post(route('attendance.clockIn'));

        // 出勤時刻を確認
        $attendance = Attendance::where('user_id', $this->user->id)->first();
        $this->assertNotNull($attendance->clock_in);
    }
}
