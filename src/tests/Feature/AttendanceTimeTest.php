<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Carbon\Carbon;

class AttendanceTimeTest extends TestCase
{
    #[Test]
    public function it_displays_the_correct_date_and_time()
    {
        // 現在の日時を取得
        $now = Carbon::now();

        $this->actingAs($this->user); // ログイン状態でリクエストを送る

        // 勤怠打刻画面を開く
        $response = $this->get(route('attendance.create'));

        // Bladeで表示される日付を検証
        $response->assertSee($now->translatedFormat('Y年n月j日(D)'));

        // Bladeで表示される時間を検証
        $response->assertSee($now->format('H:i'));
    }
}
