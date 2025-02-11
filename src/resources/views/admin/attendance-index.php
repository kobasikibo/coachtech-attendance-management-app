@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}" />
@endsection

@section('content')
<h1>{{ $attendance->getFormattedDate() }}の勤怠</h1>

<div class="date-navigation">
    <div class="navigation-item">
        <img src="{{ asset('images/icons/selector-left.png') }}" alt="前日へ移動" class="selector-icon">
        <a class="navigation-link" href="{{ route(''admin.attendance.index', ['date' => \Carbon\Carbon::parse($currentDay)->subDay()->format('Y-m-d')]) }}">前日</a>
    </div>

    <div class="navigation-item">
        <img src="{{ asset('images/icons/calendar-icon.png') }}" alt="カレンダー" class="calendar-icon">
        <span class="current-date">{{ \Carbon\Carbon::parse($currentMonth)->format('Y/m/d') }}</span>
    </div>

    <div class="navigation-item">
        <a class="navigation-link" href="{{ route('admin.attendance.index', ['date' => \Carbon\Carbon::parse($currentDay)->addDay()->format('Y-m')]) }}">翌日</a>
        <img src="{{ asset('images/icons/selector-right.png') }}" alt="翌日へ移動" class="selector-icon">
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>名前</th>
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
            <td>{{ $attendance->user->name }}</td>
            <td>{{ $attendance->getFormattedClockIn() }}</td>
            <td>{{ $attendance->getFormattedClockOut() }}</td>
            <td>{{ $attendance->getFormattedBreakTime() }}</td>
            <td>{{ $attendance->getWorkTime() }}</td>
            <td><a href="{{ route('admin.attendance.detail', $attendance->id) }}">詳細</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection