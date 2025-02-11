@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}" />
<link rel="stylesheet" href="{{ asset('css/admin-attendance-index.css') }}" />
@endsection

@section('content')
<h1>{{ \Carbon\Carbon::parse($currentDay)->format('Y年n月j日') }}の勤怠</h1>

<div class="date-navigation">
    <div class="navigation-item">
        <img src="{{ asset('images/icons/selector-left.png') }}" alt="前日へ移動" class="selector-icon">
        <a class="navigation-link" href="{{ route('admin.attendance.index', ['clock_in' => \Carbon\Carbon::parse($currentDay)->subDay()->format('Y-m-d')]) }}">前日</a>
    </div>

    <div class="navigation-item">
        <img src="{{ asset('images/icons/calendar-icon.png') }}" alt="カレンダー" class="calendar-icon">
        <span class="current-date">{{ \Carbon\Carbon::parse($currentDay)->format('Y/m/d') }}</span>
    </div>

    <div class="navigation-item">
        @if($currentDay < now()->format('Y-m-d'))
            <a class="navigation-link" href="{{ route('admin.attendance.index', ['clock_in' => \Carbon\Carbon::parse($currentDay)->addDay()->format('Y-m-d')]) }}">翌日</a>
            <img src="{{ asset('images/icons/selector-right.png') }}" alt="翌日へ移動" class="selector-icon">
            @else
            <span class="placeholder">翌日</span>
            @endif
    </div>
</div>

<table class="attendance-table">
    <thead>
        <tr>
            <th class="table-header">名前</th>
            <th class="table-header">出勤</th>
            <th class="table-header">退勤</th>
            <th class="table-header">休憩</th>
            <th class="table-header">合計</th>
            <th class="table-header">詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $attendance)
        <tr>
            <td class="table-cell">{{ $attendance->user->name }}</td>
            <td class="table-cell">{{ $attendance->getFormattedClockIn() }}</td>
            <td class="table-cell">{{ $attendance->getFormattedClockOut() }}</td>
            <td class="table-cell">{{ $attendance->getFormattedBreakTime() }}</td>
            <td class="table-cell">{{ $attendance->getWorkTime() }}</td>
            <td class="table-cell">
                <a class="table-link" href="{{ route('admin.attendance.detail', $attendance->id) }}">詳細</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection