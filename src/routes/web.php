<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\CustomEmailVerificationRequest;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WorkStatusController;

Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');
Route::post('/login', [LoginController::class, 'login'])->name('auth.login');

Route::post('/email/verification-notification', function () {
    request()->user()->sendEmailVerificationNotification();
    return back();
})->middleware(['throttle:6,1']);

Route::get('email/verify/{id}/{hash}', function (CustomEmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login');
})->middleware(['signed'])->name('verification.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendances/{id}', [AttendanceController::class, 'detail'])->name('attendance.detail');

    Route::post('/attendance/clock-in', [WorkStatusController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('/attendance/start-break', [WorkStatusController::class, 'startBreak'])->name('attendance.startBreak');
    Route::post('/attendance/end-break', [WorkStatusController::class, 'endBreak'])->name('attendance.endBreak');
    Route::post('/attendance/clock-out', [WorkStatusController::class, 'clockOut'])->name('attendance.clockOut');

    Route::put('/attendances/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
});