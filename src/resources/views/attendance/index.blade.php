@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}" />
@endsection

@section('content')
<h1>勤怠一覧</h1>

<div class="month-navigation">
    <div class="navigation-item">
        <img src="{{ asset('images/icons/selector-left.png') }}" alt="前月へ移動" class="selector-icon">
        <a class="navigation-link" href=" {{ route('attendance.index', ['month' => \Carbon\Carbon::parse($currentMonth)->subMonth()->format('Y-m')]) }}">前月</a>
    </div>

    <div class="navigation-item">
        <img src="{{ asset('images/icons/calendar-icon.png') }}" alt="カレンダー" class="calendar-icon">
        <span class="navigation-month">{{ \Carbon\Carbon::parse($currentMonth)->format('Y/m') }}</span>
    </div>

    <div class="navigation-item">
        <a class="navigation-link" href="{{ route('attendance.index', ['month' => \Carbon\Carbon::parse($currentMonth)->addMonth()->format('Y-m')]) }}">翌月</a>
        <img src="{{ asset('images/icons/selector-right.png') }}" alt="翌月へ移動" class="selector-icon">
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $attendance)
        <tr>
            <td>{{ $attendance->getFormattedDate() }}</td>
            <td>{{ $attendance->getFormattedClockIn() }}</td>
            <td>{{ $attendance->getFormattedClockOut() }}</td>
            <td>{{ $attendance->getFormattedBreakTime() }}</td>
            <td>{{ $attendance->getWorkTime() }}</td>
            <td><a href="{{ route('attendance.detail', $attendance->id) }}">詳細</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection