<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ClockInTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_clock_in()
    {
        $user = User::factory()->create();

        /** @var Authenticatable $user */
        $this->actingAs($user);

        // 出勤ボタンが表示される
        $response = $this->get(route('attendance.create'));
        $response->assertSee('btn-submit-clock-in');

        // 出勤処理を実行
        $response = $this->post(route('attendance.clockIn'));

        // ステータスが「出勤中」になったことを確認
        $attendance = Attendance::where('user_id', $user->id)
        ->where('date', today())
        ->first();

        $this->assertEquals(Attendance::STATUS_CLOCKED_IN, $attendance->status);
    }

    #[Test]
    public function user_cannot_clock_in_more_than_once_per_day()
    {
        $user = User::factory()->create();

        /** @var Authenticatable $user */
        $this->actingAs($user);

        // 出勤処理を実行
        $response = $this->post(route('attendance.clockIn'));

        // 画面上に「出勤」ボタンが表示されない
        $response = $this->get(route('attendance.create'));
        $response->assertDontSee('btn-submit-clock-in');
    }

    #[Test]
    public function clock_in_time_is_recorded()
    {
        $user = User::factory()->create();

        /** @var Authenticatable $user */
        $this->actingAs($user);

        // 出勤処理を実行
        $this->post(route('attendance.clockIn'));

        // 出勤時刻を確認
        $attendance = Attendance::where('user_id', $user->id)->first();
        $this->assertNotNull($attendance->clock_in);
    }
}
