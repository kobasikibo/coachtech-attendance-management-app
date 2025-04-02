<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
            AttendanceSeeder::class,
            BreakSeeder::class,
            AttendanceCorrectRequestSeeder::class,
            BreakCorrectRequestSeeder::class,
        ]);
    }
}

