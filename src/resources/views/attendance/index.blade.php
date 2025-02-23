@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}" />
@endsection

@section('content')
<h1>勤怠一覧</h1>

<div class="month-navigation">
    <div class="navigation-item">
        <img src="{{ asset('images/icons/selector-left.png') }}" alt="前月へ移動" class="selector-icon">
        <a class="navigation-link" href="{{ route('attendance.index', ['month' => $previousMonth]) }}">前月</a>
    </div>

    <div class="navigation-item">
        <img src="{{ asset('images/icons/calendar-icon.png') }}" alt="カレンダー" class="calendar-icon">
        <span class="navigation-month">{{ \Carbon\Carbon::parse($currentMonth)->format('Y/m') }}</span>
    </div>

    <div class="navigation-item {{ $currentMonth->format('Y-m') ==  now()->format('Y-m') ? 'invisible' : '' }}">
        <a class="navigation-link" href="{{ route('attendance.index', ['month' => $nextMonth]) }}">翌月</a>
        <img src="{{ asset('images/icons/selector-right.png') }}" alt="翌月へ移動" class="selector-icon">
    </div>
</div>

<table class="attendance-table">
    <thead>
        <tr>
            <th class="table-header">日付</th>
            <th class="table-header">出勤</th>
            <th class="table-header">退勤</th>
            <th class="table-header">休憩</th>
            <th class="table-header">合計</th>
            <th class="table-header">詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dates as $date)
        <tr>
            <td class="table-cell">{{ \Carbon\Carbon::parse($date)->translatedFormat('m/d(D)') }}</td>

            @php
            $attendanceForDate = $attendances->firstWhere('date', $date);
            @endphp
            <td class="table-cell">{{ $attendanceForDate ? $attendanceService->formatClockIn($attendanceForDate) : '' }}</td>
            <td class="table-cell">{{ $attendanceForDate ? $attendanceService->formatClockOut($attendanceForDate) : '' }}</td>
            <td class="table-cell">{{ $attendanceForDate ? $breakService->formatBreakTime($attendanceForDate) : '' }}</td>
            <td class="table-cell">{{ $attendanceForDate ? $attendanceService->formatWorkTime($attendanceForDate) : '' }}</td>
            <td class="table-cell">
                @if ($attendanceForDate)
                <a class="table-link" href="{{ route('attendance.detail', $attendanceForDate->id) }}">詳細</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection