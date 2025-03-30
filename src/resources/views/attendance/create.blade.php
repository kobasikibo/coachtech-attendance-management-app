@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-create.css') }}" />
@endsection

@section('content')

<div class="work-status">
    {{ $isAttendanceToday ? $attendance->status : '勤務外' }}
</div>

<div class="date">{!! now()->translatedFormat('Y年n月j日(D)') !!}</div>

<div class="current-time">{{ now()->format('H:i') }}</div>

<div class="btn-container">
    @if (!$attendance || !$isAttendanceToday || $attendance->status === '勤務外')
    <form action="{{ route('attendance.clockIn') }}" method="POST">
        @csrf
        <button type="submit" class="btn-submit-clock-in">出勤</button>
    </form>
    @elseif ($attendance->status === '出勤中')
    <form action="{{ route('attendance.clockOut') }}" method="POST">
        @csrf
        <button type="submit" class="btn-submit-clock-out">退勤</button>
    </form>
    <form action="{{ route('attendance.startBreak') }}" method="POST">
        @csrf
        <button type="submit" class="btn-submit-break-start">休憩入</button>
    </form>
    @elseif ($attendance->status === '休憩中')
    <form action="{{ route('attendance.endBreak') }}" method="POST">
        @csrf
        <button type="submit" class="btn-submit-break-end">休憩戻</button>
    </form>
    @endif
</div>

@if (session('success'))
<div class="success">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="error">{{ session('error') }}</div>
@endif

<script src="{{ asset('js/attendance-create.js') }}"></script>
@endsection