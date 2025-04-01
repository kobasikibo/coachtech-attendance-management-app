<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Admin;
use App\Services\AttendanceService;
use App\Services\BreakService;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected $user;
    protected $adminUser;
    protected $attendanceService;
    protected $breakService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->adminUser = Admin::factory()->create();
        $this->attendanceService = app(AttendanceService::class);
        $this->breakService = app(BreakService::class);
    }
}
