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
                    <input type="time" name="clock_in" value="{{ $attendanceService->formatClockIn($attendance) }}"
                        class="form-control-left" {{ $attendance->approval_status === 'pending' ? 'disabled' : '' }}>
                    〜
                    <input type="time" name="clock_out" value="{{ $attendanceService->formatClockOut($attendance) }}" class="form-control-right" {{ $attendance->approval_status === 'pending' ? 'disabled' : '' }}>
                </div>
            </div>

            @error('clock_in')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        @forelse ($formattedBreaks as $index => $break)
        <div class="form-break">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">休憩 {{ $index + 1 }}</label>
                </div>
                <div class="form-input-container">
                    <input type="hidden" name="break_id[{{ $index }}]" value="{{ $break['id'] }}">

                    <input type="time" name="breaks[{{ $break['id'] }}][break_start]" value="{{ $break['break_start'] }}" class="form-control-left" {{ $attendance->approval_status === 'pending' ? 'disabled' : '' }}>
                    〜
                    <input type="time" name="breaks[{{ $break['id'] }}][break_end]" value="{{ $break['break_end'] }}" class="form-control-right" {{ $attendance->approval_status === 'pending' ? 'disabled' : '' }}>
                </div>
            </div>
            @foreach ($errors->get("breaks.$index.break_start") as $message)
            <div class="error">{{ $message }}</div>
            @endforeach
        </div>
        @empty
        <!-- 休憩情報が一つもない場合 -->
        <div class="form-break">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">休憩</label>
                </div>
                <div class="form-input-container">
                    <input type="time" name="breaks[0][break_start]" class="form-control-left">
                    〜
                    <input type="time" name="breaks[0][break_end]" class="form-control-right">
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
                    <textarea name="remarks" class="form-control-large" {{ $attendance->approval_status === 'pending' ? 'disabled' : '' }}>{{ $attendance->remarks }}</textarea>
                </div>
            </div>

            @error('remarks')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @if($attendance->approval_status === 'pending')
    <div class="alert">
        *承認待ちのため修正はできません。
    </div>
    @endif

    <button type="submit" class="btn-submit {{ $attendance->approval_status === 'pending' ? 'invisible' : '' }}">修正</button>
</form>

@endsection