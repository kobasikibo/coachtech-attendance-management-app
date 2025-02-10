@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}" />
@endsection

@section('content')
<h1>勤怠一覧</h1>

<div class="month-navigation">
    <a class="navigation-link" href=" {{ route('attendance.index', ['month' => \Carbon\Carbon::parse($currentMonth)->subMonth()->format('Y-m')]) }}">前月</a>
    <strong>{{ \Carbon\Carbon::parse($currentMonth)->format('Y/m') }}</strong>
    <a class="navigation-link" href="{{ route('attendance.index', ['month' => \Carbon\Carbon::parse($currentMonth)->addMonth()->format('Y-m')]) }}">翌月</a>
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