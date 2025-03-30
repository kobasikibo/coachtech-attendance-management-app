<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Carbon\Carbon;

class AttendanceTimeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_correct_date_and_time()
    {
        // 現在の日時を取得
        $now = Carbon::now();

        /** @var Authenticatable $user */
        $user = User::factory()->create(); // テスト用のユーザーを作成
        $this->actingAs($user); // ログイン状態でリクエストを送る

        // 勤怠打刻画面を開く
        $response = $this->get(route('attendance.create'));

        // Bladeで表示される日付を検証
        $response->assertSee($now->translatedFormat('Y年n月j日(D)'));

        // Bladeで表示される時間を検証
        $response->assertSee($now->format('H:i'));
    }
}
