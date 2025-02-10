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
        <div class="form-row">
            <div class="form-row-left">
                <label class="form-label">名前</label>
            </div>
            <div class="form-row-right">
                <p class="name">{{ $attendance->user->name ?? 'ー' }}</p>
            </div>
        </div>

        <div class="form-row">
            <div class="form-row-left">
                <label class="form-label">日付</label>
            </div>
            <div class="form-row-right">
                <input type="text" name="year" value="{{ $attendance->created_at->format('Y') }}" readonly class="form-control">
                <input type="text" name="month_day" value="{{ $attendance->created_at->format('m-d') }}" readonly class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-row-left">
                <label class="form-label">出勤・退勤</label>
            </div>
            <div class="form-row-right">
                <input type="text" name="clock_out" value="{{ $attendance->getFormattedClockOut() }}" class="form-control">
                <input type="text" name="clock_in" value="{{ $attendance->getFormattedClockIn() }}" class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-row-left">
                <label class="form-label">休憩</label>
            </div>
            <div class="form-row-right">
                <input type="text" name="break_start" value="{{ $attendance->getFormattedBreakStart() }}" class="form-control">
                <input type="text" name="break_end" value="{{ $attendance->getFormattedBreakEnd() }}" class="form-control">
            </div>
        </div>

        <div class="form-row-large">
            <div class="form-row-left">
                <label class="form-label">備考</label>
            </div>
            <div class="form-row-right">
                <textarea name="remarks" rows="3" class="form-control-large">{{ $attendance->remarks }}</textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-submit">修正</button>
</form>

@endsection