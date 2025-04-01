<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\AttendanceCorrectRequest;

class AdminAttendanceApprovedTest extends TestCase
{
    #[Test]
    public function pending_requests_are_displayed_correctly(): void
    {
        // 全ユーザーの未承認の修正申請が表示される
        $requests = AttendanceCorrectRequest::factory()->count(3)->create([
            'status' => AttendanceCorrectRequest::STATUS_PENDING
        ]);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get('/admin/stamp_correction_request/list?tab=pending');

        $response->assertStatus(200);
        foreach ($requests as $request) {
            $response->assertSee($request->remarks);
        }
    }

    #[Test]
    public function approved_requests_are_displayed_correctly(): void
    {
        $requests = AttendanceCorrectRequest::factory()->count(3)->create(['status' => 'approved']);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get('/admin/stamp_correction_request/list?tab=approved');

        $response->assertStatus(200);
        foreach ($requests as $request) {
            $response->assertSee($request->remarks);
        }
    }

    #[Test]
    public function correction_request_details_are_displayed_correctly(): void
    {
        $request = AttendanceCorrectRequest::factory()->create();

        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.stamp_correction_request.show', $request->id));

        $response->assertStatus(200);
        $response->assertSee($request->remarks);
    }

    #[Test]
    public function correction_request_can_be_approved(): void
    {
        $request = AttendanceCorrectRequest::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->put(route('admin.stamp_correction_request.approve', $request->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('stamp_correction_requests', [
            'id' => $request->id,
            'status' => 'approved',
        ]);
    }
}

