<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceCorrectRequest;

class AdminAttendanceApprovedTest extends TestCase
{
    #[Test]
    public function pending_requests_are_displayed_correctly(): void
    {
        // 全ユーザーの承認待ちの修正申請が表示される
        $users = User::factory()->count(3)->create();
        $attendances = $users->map(fn($user) => Attendance::factory()->create([
            'user_id' => $user->id,
        ]));
        $requests = $attendances->map(function ($attendance) {
            return AttendanceCorrectRequest::factory()->create([
                'attendance_id' => $attendance->id,
                'status' => AttendanceCorrectRequest::STATUS_PENDING
            ]);
        });

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get('/admin/stamp_correction_request/list?tab=pending')
            ->assertStatus(200);

        foreach ($requests as $request) {
            $response->assertSee($request->name);
        }
    }

    #[Test]
    public function approved_requests_are_displayed_correctly(): void
    {
        // 全ユーザーの承認済みの修正申請が表示される
        $users = User::factory()->count(3)->create();
        $attendances = $users->map(fn($user) => Attendance::factory()->create([
            'user_id' => $user->id,
        ]));
        $requests = $attendances->map(function ($attendance) {
            return AttendanceCorrectRequest::factory()->create([
                'attendance_id' => $attendance->id,
                'status' => AttendanceCorrectRequest::STATUS_APPROVED
            ]);
        });

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get('/admin/stamp_correction_request/list?tab=approved')
            ->assertStatus(200);

        foreach ($requests as $request) {
            $response->assertSee($request->name);
        }
    }

    #[Test]
    public function correction_request_details_are_displayed_correctly(): void
    {
        // 修正申請の詳細が正しく表示されている
        $attendance = Attendance::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $request = AttendanceCorrectRequest::factory()->create([
            'attendance_id' => $attendance->id,
            'status' => AttendanceCorrectRequest::STATUS_APPROVED,
        ]);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.stamp_correction_request.show', $request->id))
            ->assertStatus(200);

        $response->assertSee($request->name);
        $response->assertSee($attendance->date->translatedFormat('Y年'));
        $response->assertSee($attendance->date->translatedFormat('n月j日'));
        $response->assertSee($request->remarks);
    }

    #[Test]
    public function correction_request_can_be_approved(): void
    {
        // 修正申請が承認され、勤怠情報が更新される
        $attendance = Attendance::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $request = AttendanceCorrectRequest::factory()->create([
            'attendance_id' => $attendance->id,
            'status' => AttendanceCorrectRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->adminUser, 'admin')
            ->put(route('admin.stamp_correction_request.approve', $request->id))
            ->assertRedirect(route('admin.stamp_correction_request.show', ['attendance_correct_request' => $request->id]))
            ->assertDontSeeText('承認 ');

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.stamp_correction_request.show', $request->id))
            ->assertStatus(200);

        $response->assertSee($request->name);
        $response->assertSee($attendance->date->translatedFormat('Y年'));
        $response->assertSee($attendance->date->translatedFormat('n月j日'));
        $response->assertSee($request->remarks);
    }
}

