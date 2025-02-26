<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminStampCorrectionRequestController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{id}', [AdminAttendanceController::class, 'detail'])->name('attendance.detail');
    Route::put('/attendances/{id}', [AdminAttendanceController::class, 'update'])->name('attendance.update');

    Route::get('/stamp_correction_request/list', [AdminStampCorrectionRequestController::class, 'index'])
    ->name('stamp_correction_request.index');
});