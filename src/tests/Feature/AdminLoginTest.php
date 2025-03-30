<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);
    }

    #[Test]
    public function email_is_required_to_login()
    {
        $response = $this->post(route('admin.login'), [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    #[Test]
    public function password_is_required_to_login()
    {
        $response = $this->post(route('admin.login'), [
            'email' => 'admin@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    #[Test]
    public function incorrect_credentials_show_validation_error()
    {
        $response = $this->post(route('admin.login'), [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }
}
