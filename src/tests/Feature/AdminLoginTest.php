<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminLoginTest extends TestCase
{
    public function email_is_required_to_login()
    {
        // メールアドレスが未入力の場合、バリデーションメッセージが表示される
        $response = $this->post(route('admin.login'), [
            'email' => '',
            'password' => 'admin123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    #[Test]
    public function password_is_required_to_login()
    {
        // パスワードが未入力の場合、バリデーションメッセージが表示される
        $response = $this->post(route('admin.login'), [
            'email' => 'admin@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    #[Test]
    public function incorrect_credentials_show_validation_error()
    {
        // 登録内容と一致しない場合、バリデーションメッセージが表示される
        $response = $this->post(route('admin.login'), [
            'email' => 'wrong@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }
}
