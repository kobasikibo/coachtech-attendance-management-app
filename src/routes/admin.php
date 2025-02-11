<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAttendanceController;
use Illuminate\Support\Facades\Log;

Route::middleware(['auth:admin'])->group(function () {
    Log::info('Admin guard is working');
    Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/detail/{attendanceId}', [AdminAttendanceController::class, 'show'])->name('attendance.detail');
});