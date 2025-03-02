<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AdminStampCorrectionRequestController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{id}', [AdminAttendanceController::class, 'detail'])->name('attendance.detail');
    Route::put('/attendance/{id}', [AdminAttendanceController::class, 'update'])->name('attendance.update');

    Route::get('/staff/list', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/attendance/staff/{id}', [AdminAttendanceController::class, 'monthly'])->name('attendance.monthly');

    Route::get('/stamp_correction_request/list', [AdminStampCorrectionRequestController::class, 'index'])
    ->name('stamp_correction_request.index');
});