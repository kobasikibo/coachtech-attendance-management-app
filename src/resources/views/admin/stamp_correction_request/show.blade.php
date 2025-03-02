@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}" />
@endsection

@section('content')
<h1>勤怠詳細</h1>

<form method="POST" action="{{ route('attendance.update', $attendance->id) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <div class="form-name">
            <div class="form-label-container">
                <label class="form-label">名前</label>
            </div>
            <div class="form-input-container">
                <p class="name">{{ $attendance->user->name ?? '' }}</p>
            </div>
        </div>

        <div class="form-date">
            <div class="form-label-container">
                <label class="form-label">日付</label>
            </div>
            <div class="form-input-container">
                <p class="attendance-year">{{ $attendanceService->getYearFromClockIn($attendance) }}</p>
                <p class="attendance-date">{{ $attendanceService->getMonthDayFromClockIn($attendance) }}</p>
            </div>
        </div>

        <div class="form-clock">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">出勤・退勤</label>
                </div>
                <div class="form-input-container">
                    <p class="form-control-left">{{ $attendanceService->formatClockIn($attendance) }}</p>
                    〜
                    <p class="form-control-right">{{ $attendanceService->formatClockOut($attendance) }}</p>
                </div>
            </div>
        </div>

        @forelse ($formattedBreaks as $index => $break)
        <div class="form-break">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">休憩 {{ $index + 1 }}</label>
                </div>
                <div class="form-input-container">
                    <p class="form-control-left">{{ $break['break_start'] }}</p>
                    〜
                    <p class="form-control-right">{{ $break['break_end'] }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="form-break">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">休憩</label>
                </div>
                <div class="form-input-container">
                    <p class="form-control-left">-</p>
                    〜
                    <p class="form-control-right">-</p>
                </div>
            </div>
        </div>
        @endforelse

        <div class="form-remarks">
            <div class="form-row-large">
                <div class="form-label-container">
                    <label class="form-label">備考</label>
                </div>
                <div class="form-input-container">
                    <p class="form-control-large">{{ $attendance->remarks }}</p>
                </div>
            </div>
        </div>
    </div>

<button type="submit" class="btn-submit {{ $attendance->approval_status === '承認済み' ? 'approved' : '' }}"
    {{ $attendance->approval_status === '承認済み' ? 'disabled' : '' }}>
    {{ $attendance->approval_status === '承認済み' ? '承認済み' : '承認' }}
</button>

@endsection