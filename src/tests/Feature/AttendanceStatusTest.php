<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Attendance;

class AttendanceStatusTest extends TestCase
{
    // 勤務外のステータス
    #[Test]
    public function test_status_is_off_duty_for_off_duty_user()
    {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_OFF_DUTY,
            'date' => today(),
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('attendance.create'));
        $response->assertSee('勤務外');
    }

    // 勤務中のステータス
    #[Test]
    public function test_status_is_clocked_in_for_clocked_in_user()
    {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('attendance.create'));
        $response->assertSee('出勤中');
    }

    // 休憩中のステータス
    #[Test]
    public function test_status_is_on_break_for_on_break_user()
    {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_ON_BREAK,
            'date' => today(),
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('attendance.create'));
        $response->assertSee('休憩中');
    }

    // 退勤済のステータス
    #[Test]
    public function test_status_is_clocked_out_for_clocked_out_user()
    {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'status' => Attendance::STATUS_CLOCKED_OUT,
            'date' => today(),
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('attendance.create'));
        $response->assertSee('退勤済');
    }
}
