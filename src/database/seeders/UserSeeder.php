<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 管理者を手動で作成
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpass'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);

        User::factory(10)->create();
    }
}
