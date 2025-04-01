<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceIndexTest extends TestCase
{
    // 自分の勤怠情報が全て表示されている
    #[Test]
    public function all_attendance_records_are_displayed()
    {
        // 勤怠情報が登録されたユーザーで、勤怠一覧ページを開く
        $response = $this->actingAs($this->user)->get(route('attendance.index'));
        $response->assertStatus(200);

        // 自分の勤怠情報が全て表示されている
        $response->assertSeeInOrder(
            Attendance::where('user_id', $this->user->id)
            ->orderBy('date')
                ->pluck('date')
                ->toArray()
        );
    }

    #[Test]
    // 勤怠一覧画面に遷移した際に現在の月が表示される
    public function current_month_is_displayed_on_attendance_index()
    {
        // ユーザーが勤怠一覧ページを開く
        $response = $this->actingAs($this->user)->get(route('attendance.index'));
        $response->assertStatus(200);

        // 現在の月が表示されている
        $response->assertSee(Carbon::now()->format('Y/m'));
    }

    #[Test]
    // 「前月」を押下した時に表示月の前月の情報が表示される
    public function previous_month_button_displays_previous_month_attendance()
    {
        // 勤怠情報が登録されたユーザーで、勤怠一覧ページを開く
        $response = $this->actingAs($this->user)->get(route('attendance.index'));

        // 「前月」ボタンを押す
        $previousMonth = Carbon::now()->subMonth()->format('Y/m');
        $response = $this->actingAs($this->user)->get(route('attendance.index', ['month' => $previousMonth]));

        // 前月の情報が表示されている
        $response->assertSee($previousMonth);
    }

    #[Test]
    // 「翌月」を押下した時に表示月の前月の情報が表示される
    public function next_month_button_displays_next_month_attendance()
    {
        // 勤怠情報が登録されたユーザーで、勤怠一覧ページを開く
        $response = $this->actingAs($this->user)->get(route('attendance.index'));

        // 「翌月」ボタンを押す
        $nextMonth = Carbon::now()->addMonth()->format('Y/m');
        $response = $this->actingAs($this->user)->get(route('attendance.index', ['month' => $nextMonth]));

        // 翌月の情報が表示されている
        $response->assertSee($nextMonth);
    }

    #[Test]
    // 「詳細」を押下すると、その日の勤怠詳細画面に遷移する
    public function detail_button_navigates_to_attendance_detail_page()
    {
        // テスト用に特定の日付の勤怠データを作成
        $date = Carbon::now();
        $attendance = Attendance::factory()->create([
            'user_id' => $this->user->id,
            'date' => $date,
        ]);

        // 勤怠情報が登録されたユーザーで、勤怠一覧ページを開く
        $response = $this->actingAs($this->user)->get(route('attendance.index'));

        // 詳細ボタンを押下する
        $response = $this->get(route('attendance.show', $attendance->id));

        // その日の勤怠詳細画面に遷移する
        $response->assertSee(Carbon::parse($attendance->date)->format('n月j日'));
    }
}
