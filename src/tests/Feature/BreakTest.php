<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Attendance;
use App\Models\BreakModel;

class BreakTest extends TestCase
{
    #[Test]
    public function user_can_start_break()
    {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
        ]);

        $this->actingAs($this->user);

        // 画面上に「休憩入」ボタンが表示されていることを確認
        $response = $this->get(route('attendance.create'));
        $response->assertSee('btn-submit-break-start');

        // 休憩処理を実行
        $response = $this->post(route('attendance.startBreak'));

        // ステータスが「休憩中」になったことを確認
        $attendance = Attendance::where('user_id', $this->user->id)
        ->where('date', today())
        ->first();
        $this->assertEquals(Attendance::STATUS_ON_BREAK, $attendance->status);
    }

    #[Test]
    public function user_can_start_break_multiple_times_per_day()
    {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
        ]);

        $this->actingAs($this->user);

        // 休憩入の処理を実行
        $this->post(route('attendance.startBreak'));

        // ステータスが「休憩中」になったことを確認
        $attendance = Attendance::where('user_id', $this->user->id)
            ->where('date', today())
            ->first();
        $this->assertEquals(Attendance::STATUS_ON_BREAK, $attendance->status);

        // 休憩戻の処理を実行
        $this->post(route('attendance.endBreak'));

        // ステータスが「出勤中」になったことを確認
        $attendance = Attendance::where('user_id', $this->user->id)
            ->where('date', today())
            ->first();
        $this->assertEquals(Attendance::STATUS_CLOCKED_IN, $attendance->status);

        // 画面上に「休憩入」ボタンが表示されていることを確認
        $response = $this->get(route('attendance.create'));
        $response->assertSee('btn-submit-break-start');
    }

    #[Test]
    public function user_can_end_break()
    {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
        ]);

        $this->actingAs($this->user);

        // 休憩入の処理を実行
        $this->post(route('attendance.startBreak'));

        // 休憩戻ボタンが表示されることを確認
        $response = $this->get(route('attendance.create'));
        $response->assertSee('btn-submit-break-end');

        // 休憩戻の処理を実行
        $this->post(route('attendance.endBreak'));

        // ステータスが「出勤中」になったことを確認
        $attendance = Attendance::where('user_id', $this->user->id)
            ->where('date', today())
            ->first();
        $this->assertEquals(Attendance::STATUS_CLOCKED_IN, $attendance->status);
    }

    #[Test]
    public function user_can_end_break_multiple_times_per_day()
    {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
        ]);

        $this->actingAs($this->user);

        // 休憩入の処理を実行
        $this->post(route('attendance.startBreak'));

        // 休憩戻の処理を実行
        $this->post(route('attendance.endBreak'));

        // 休憩入の処理を実行
        $this->post(route('attendance.startBreak'));

        // 画面上に「休憩戻」ボタンが表示されていることを確認
        $response = $this->get(route('attendance.create'));
        $response->assertSee('btn-submit-break-end');
    }

    #[Test]
    public function break_times_are_recorded()
    {
        $attendance = Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
        ]);

        $this->actingAs($this->user);

        // 休憩処理を実行
        $this->post(route('attendance.startBreak'));
        $this->post(route('attendance.endBreak'));

        // 休憩データを取得
        $break = BreakModel::where('attendance_id', $attendance->id)->first();

        // 休憩時間が正確に記録されていることを確認
        $this->assertNotNull($break);
        $this->assertNotNull($break->break_start);
        $this->assertNotNull($break->break_end);
    }
}