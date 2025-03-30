<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AttendanceStatusTest extends TestCase
{
    use RefreshDatabase;

    // 勤務外のステータス
    #[Test]
    public function test_status_is_off_duty_for_off_duty_user()
    {
        $user = User::factory()->create();
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => Attendance::STATUS_OFF_DUTY,
            'date' => today(),
        ]);

        /** @var Authenticatable $user */
        $this->actingAs($user);
        $response = $this->get(route('attendance.create'));
        $response->assertSee('勤務外');
    }

    // 勤務中のステータス
    #[Test]
    public function test_status_is_clocked_in_for_clocked_in_user()
    {
        $user = User::factory()->create();
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => Attendance::STATUS_CLOCKED_IN,
            'date' => today(),
        ]);

        /** @var Authenticatable $user */
        $this->actingAs($user);
        $response = $this->get(route('attendance.create'));
        $response->assertSee('出勤中');
    }

    // 休憩中のステータス
    #[Test]
    public function test_status_is_on_break_for_on_break_user()
    {
        $user = User::factory()->create();
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => Attendance::STATUS_ON_BREAK,
            'date' => today(),
        ]);

        /** @var Authenticatable $user */
        $this->actingAs($user);
        $response = $this->get(route('attendance.create'));
        $response->assertSee('休憩中');
    }

    // 退勤済のステータス
    #[Test]
    public function test_status_is_clocked_out_for_clocked_out_user()
    {
        $user = User::factory()->create();
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => Attendance::STATUS_CLOCKED_OUT,
            'date' => today(),
        ]);

        /** @var Authenticatable $user */
        $this->actingAs($user);
        $response = $this->get(route('attendance.create'));
        $response->assertSee('退勤済');
    }
}
