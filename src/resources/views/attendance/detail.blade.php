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
                <p class="name">{{ $attendance->user->name ?? 'ー' }}</p>
            </div>
        </div>

        <div class="form-date">
            <div class="form-label-container">
                <label class="form-label">日付</label>
            </div>
            <div class="form-input-container">
                <p class="attendance-year">{{ $attendance->getYearFromClockInAttribute() }}</p>
                <p class="attendance-date">{{ $attendance->getMonthDayFromClockInAttribute() }}</p>
            </div>
        </div>

        <div class="form-clock">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">出勤・退勤</label>
                </div>
                <div class="form-input-container">
                    <input type="text" name="clock_in" value="{{ $attendance->getFormattedClockIn() }}"
                        class="form-control-left">
                    〜
                    <input type="text" name="clock_out" value="{{ $attendance->getFormattedClockOut() }}" class="form-control-right">
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

                    <input type="text" name="breaks[{{ $break['id'] }}][break_start]" id="break_start_{{ $index + 1 }}" value="{{ $break['break_start'] }}" class="form-control-left">

                    〜

                    <input type="text" name="breaks[{{ $break['id'] }}][break_end]" id="break_end_{{ $index + 1 }}" value="{{ $break['break_end'] }}" class="form-control-right">
                </div>
            </div>

            @error('break_start.' . $index)
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
        @empty
        <!-- 休憩情報が一つもない場合 -->
        <div class="form-break">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">休憩 1</label>
                </div>
                <div class="form-input-container">
                    <input type="hidden" name="break_id[0]" value="0">

                    <input type="text" name="breaks[0][break_start]" id="break_start_1" class="form-control-left">

                    〜

                    <input type="text" name="breaks[0][break_end]" id="break_end_1" class="form-control-right">
                </div>
            </div>
        </div>
        @endempty

        <div class="form-remarks">
            <div class="form-row-large">
                <div class="form-label-container">
                    <label class="form-label">備考</label>
                </div>
                <div class="form-input-container">
                    <textarea name="remarks" rows="3" class="form-control-large">{{ $attendance->remarks }}</textarea>
                </div>
            </div>

            @error('remarks')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <button type="submit" class="btn-submit">修正</button>
</form>

<script src="{{ asset('js/time-format.js') }}"></script>
@endsection