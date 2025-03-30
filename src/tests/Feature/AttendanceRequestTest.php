<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakModel;
use App\Models\Admin;
use App\Models\AttendanceCorrectRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Carbon\Carbon;

class AttendanceRequestTest extends TestCase
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

    // 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
    #[Test]
    public function clock_in_cannot_be_after_clock_out()
    {
        // 出勤時間を退勤時間より後に設定する
        $response = $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '19:00',
            'clock_out' => '18:00',
            'remarks' => '勤怠修正'
        ]);

        // バリデーションメッセージが表示される
        $response->assertSessionHasErrors(['clock_in' => '出勤時間もしくは退勤時間が不適切な値です']);
    }

    // 休憩開始時間が退勤時間より後になっている場合、エラーメッセージが表示される
    #[Test]
    public function break_start_cannot_be_after_clock_out()
    {
        // 休憩開始時間を退勤時間より後に設定する
        $response = $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'breaks' => [
                [
                    'break_start' => '18:01',
                    'break_end' => '18:02',
                ]
            ],
            'remarks' => '勤怠修正'
        ]);

        // バリデーションメッセージが表示される
        $response->assertSessionHasErrors(['breaks.0.break_end' => '休憩時間が勤務時間外です']);
    }

    // 休憩終了時間が退勤時間より後になっている場合、エラーメッセージが表示される
    #[Test]
    public function break_end_cannot_be_after_clock_out()
    {
        // 休憩終了時間を退勤時間より後に設定する
        $response = $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'breaks' => [
                [
                    'break_start' => '17:30',
                    'break_end' => '18:30',
                ]
            ],
            'remarks' => '勤怠修正'
        ]);

        // バリデーションメッセージが表示される
        $response->assertSessionHasErrors(['breaks.0.break_end' => '休憩時間が勤務時間外です']);
    }

    // 備考欄が未入力の場合のエラーメッセージが表示される
    #[Test]
    public function remarks_is_required()
    {
        // 備考欄を未入力のまま保存処理をする
        $response = $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_start' => '12:00',
            'break_end' => '13:00',
        ]);

        // バリデーションメッセージが表示される
        $response->assertSessionHasErrors(['remarks' => '備考を記入してください']);
    }

    // 修正申請処理が実行される
    #[Test]
    public function attendance_request_is_submitted()
    {
        // 勤怠詳細を修正し保存処理をする
        $response = $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '09:00',
            'clock_out' => '20:00',
            'remarks' => '勤怠修正'
        ]);

        // 勤怠修正リクエストが自動的に作成されることを確認する
        $attendanceCorrectRequest = AttendanceCorrectRequest::where('attendance_id', $this->attendance->id)->first();

        // テスト用の管理者ユーザーを作成する
        /** @var Authenticatable $adminUser */
        $adminUser = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpass'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($adminUser, 'admin'); // テスト用の管理者ユーザーでログインする

        // 承認画面を確認する
        $response = $this->get(route( 'admin.stamp_correction_request.show', $attendanceCorrectRequest->id));

        // 承認画面に表示される
        $response->assertSee('承認');
        $response->assertDontSee('承認済み');

        // 申請一覧画面を確認する
        $response = $this->get(route('stamp_correction_request.index'));

        // 申請一覧画面に表示される
        $response->assertSee('承認待ち');
    }

    // 「承認待ち」にログインユーザーが行った申請が全て表示されていること
    #[Test]
    public function user_sees_all_own_requests_in_the_list()
    {
        // 勤怠詳細を修正し保存処理をする
        $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '09:00',
            'clock_out' => '20:00',
            'remarks' => '勤怠修正1'
        ]);
        $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '08:30',
            'clock_out' => '17:30',
            'remarks' => '勤怠修正2'
        ]);
        $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'remarks' => '勤怠修正3'
        ]);

        // 申請一覧画面を確認する
        $response = $this->get(route('stamp_correction_request.index'));

        // 承認待ちに自分の申請が全て表示されている
        $response->assertSee('勤怠修正1');
        $response->assertSee('勤怠修正2');
        $response->assertSee('勤怠修正3');
    }

    // 承認済みに管理者が承認した修正申請が全て表示される
    #[Test]
    public function admin_sees_all_approved_requests()
    {
        // 勤怠詳細を修正し保存処理をする
        $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '09:00',
            'clock_out' => '20:00',
            'remarks' => '勤怠修正1'
        ]);
        $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '08:30',
            'clock_out' => '17:30',
            'remarks' => '勤怠修正2'
        ]);
        $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'remarks' => '勤怠修正3'
        ]);

        // 各勤怠修正リクエストが自動的に作成されることを確認する
        $attendanceCorrectRequests = AttendanceCorrectRequest::where('attendance_id', $this->attendance->id)->get();
        $this->assertCount(3, $attendanceCorrectRequests);

        // テスト用の管理者ユーザーを作成する
        /** @var Authenticatable $adminUser */
        $adminUser = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpass'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($adminUser,
            'admin'
        ); // テスト用の管理者ユーザーでログインする

        // すべての修正申請を承認する
        foreach ($attendanceCorrectRequests as $request) {
            $this->put(route('admin.stamp_correction_request.approve', $request->id));
        }

        // 再度ユーザーでログインする
        $this->actingAs($this->user);

        // 申請一覧画面の承認済みタブを確認する
        $response = $this->get(route('stamp_correction_request.index') . '?tab=approved');

        // 承認済みに管理者が承認した申請が全て表示されている
        $response->assertSee('勤怠修正1');
        $response->assertSee('勤怠修正2');
        $response->assertSee('勤怠修正3');
    }

    // 各申請の「詳細」を押下すると申請詳細画面に遷移する
    #[Test]
    public function user_can_view_application_detail()
    {
        // 勤怠詳細を修正し保存処理をする
        $this->put(route('attendance.request', $this->attendance->id), [
            'clock_in' => '09:00',
            'clock_out' => '20:00',
            'remarks' => '勤怠修正'
        ]);

        // 勤怠修正リクエストが自動的に作成されることを確認する
        $attendanceCorrectRequest = AttendanceCorrectRequest::where('attendance_id', $this->attendance->id)->first();

        // 申請一覧画面を表示する
        $this->get(route('stamp_correction_request.index'));

        // 「詳細」ボタンを押す
        $response = $this->get(route('stamp_correction_request.show', $attendanceCorrectRequest->id));

        // 申請詳細画面に遷移する
        $response->assertSee('*承認待ちのため修正はできません。');
    }
}
