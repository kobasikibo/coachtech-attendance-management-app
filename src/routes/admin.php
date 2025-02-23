<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAttendanceController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/detail/{attendanceId}', [AdminAttendanceController::class, 'show'])->name('attendance.detail');
    Route::get('/stamp_correction_request/list', [AdminStampCorrectionRequestController::class, 'index'])
    ->name('admin.stamp_correction_request.index');
});