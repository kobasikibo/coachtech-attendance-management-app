@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request-show.css') }}" />
@endsection

@section('content')
<h1>勤怠詳細</h1>

<div class="attendance-detail">
    <div class="attendance-detail__name">
        <div class="attendance-detail__label-wrapper">
            <label class="attendance-detail__label">名前</label>
        </div>
        <div class="attendance-detail__content">
            <p class="name">{{ $attendance->user->name ?? '' }}</p>
        </div>
    </div>

    <div class="attendance-detail__date">
        <div class="attendance-detail__label-wrapper">
            <label class="attendance-detail__label">日付</label>
        </div>
        <div class="attendance-detail__content">
            <p class="year">{{ $attendanceService->getYearFromDate($attendance) }}</p>
            <p class="date">{{ $attendanceService->getMonthDayFromDate($attendance) }}</p>
        </div>
    </div>

    <div class="attendance-detail__clock">
        <div class="attendance-detail__row">
            <div class="attendance-detail__label-wrapper">
                <label class="attendance-detail__label">出勤・退勤</label>
            </div>
            <div class="attendance-detail__content">
                <p class="attendance-detail__left">{{ $correctionRequest->formatClockIn() }}</p>
                〜
                <p class="attendance-detail__right">{{ $correctionRequest->formatClockOut() }}</p>
            </div>
        </div>
    </div>

    @forelse ($formattedBreaks as $index => $break)
    <div class="attendance-detail__break">
        <div class="attendance-detail__row">
            <div class="attendance-detail__label-wrapper">
                <label class="attendance-detail__label">{{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}</label>
            </div>
            <div class="attendance-detail__content">
                <p class="attendance-detail__left">{{ $break['break_start'] ?? '-' }}</p>
                〜
                <p class="attendance-detail__right">{{ $break['break_end'] ?? '-' }}</p>
            </div>
        </div>
    </div>
    @empty
    <div class="attendance-detail__break">
        <div class="attendance-detail__row">
            <div class="attendance-detail__label-wrapper">
                <label class="attendance-detail__label">休憩</label>
            </div>
            <div class="attendance-detail__content">
                <p class="attendance-detail__left">-</p>
                〜
                <p class="attendance-detail__right">-</p>
            </div>
        </div>
    </div>
    @endforelse

    <div class="attendance-detail__remarks">
        <div class="attendance-detail__row">
            <div class="attendance-detail__label-wrapper">
                <label class="attendance-detail__label">備考</label>
            </div>
            <div class="attendance-detail__content">
                <p class="attendance-detail__large">{{ $correctionRequest->remarks }}</p>
            </div>
        </div>
    </div>
</div>

@if($correctionRequest->status === \App\Models\AttendanceCorrectRequest::STATUS_PENDING)
<div class="alert">
    *承認待ちのため修正はできません。
</div>
@endif

@endsection