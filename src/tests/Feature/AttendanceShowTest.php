<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakModel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Carbon\Carbon;

class AttendanceShowTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $attendance;
    private $break;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Authenticatable $user */
        $this->user = User::factory()->create(); // テスト用のユーザーを作成
        $this->actingAs($this->user); // テスト用のユーザーでログインする

        // テスト用に「特定の日付」の勤怠データを作成
        $date = Carbon::now();
        $this->attendance = Attendance::factory()->create([
            'user_id' => $this->user->id,
            'date' => $date->toDateString(),
            'clock_in' => $date->copy()->setTime(9, 0),
            'clock_out' => $date->copy()->setTime(18, 0),
        ]);

        $this->break = BreakModel::factory()->create([
            'attendance_id' => $this->attendance->id,
            'break_start' => $date->copy()->setTime(12, 0),
            'break_end' => $date->copy()->setTime(13, 0),
        ]);
    }

    // 勤怠詳細画面の「名前」がログインユーザーの氏名になっている
    #[Test]
    public function detail_page_displays_correct_user_name()
    {
        // 勤怠詳細ページを開く
        $response = $this->get(route('attendance.show', $this->attendance->id));

        // 名前欄を確認する
        $response->assertSee($this->user->name);
    }

    // 勤怠詳細画面の「日付」が選択した日付になっている
    #[Test]
    public function detail_page_displays_correct_date()
    {
        // 勤怠詳細ページを開く
        $response = $this->get(route('attendance.show', $this->attendance->id));

        // 日付欄を確認する
        $response->assertSee(Carbon::parse($this->attendance->date)->format('n月j日'));
    }

    // 「出勤・退勤」にて記されている時間がログインユーザーの打刻と一致している
    #[Test]
    public function detail_page_displays_correct_working_times()
    {
        // 勤怠詳細ページを開く
        $response = $this->get(route('attendance.show', $this->attendance->id));

        // 出勤・退勤欄を確認する
        $response->assertSee($this->attendance-> clock_in->format('H:i'));
        $response->assertSee($this->attendance->clock_out->format('H:i'));
    }

    #[Test]
    public function detail_page_displays_correct_break_times()
    {
        // 勤怠詳細ページを開く
        $response = $this->get(route('attendance.show', $this->attendance->id));

        // 休憩情報を取得
        $break = $this->attendance->breaks->first();

        // 休憩欄を確認する
        $response->assertSee($break->break_start->format('H:i'));
        $response->assertSee($break->break_end->format('H:i'));
    }
}
