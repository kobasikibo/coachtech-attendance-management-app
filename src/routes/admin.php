<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StampCorrectionRequestController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');

    Route::get('/staff/list', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/attendance/staff/{id}', [AttendanceController::class, 'indexStaffAttendance'])->name('attendance.staff');
    Route::get('admin/attendance/{id}/csv', [AttendanceController::class, 'exportCsv'])->name('attendance.export');

    Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])
    ->name('stamp_correction_request.index');
    Route::get('/stamp_correction_request/{attendance_correct_request}', [StampCorrectionRequestController::class, 'show'])
    ->name('stamp_correction_request.show');
    Route::put('/stamp_correction_request/approve/{attendance_correct_request}', [StampCorrectionRequestController::class, 'approve'])
    ->name('stamp_correction_request.approve');
});