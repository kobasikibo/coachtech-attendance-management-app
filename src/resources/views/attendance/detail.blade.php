@extends('layouts.app')

@section('content')
    <h1>勤怠詳細</h1>
    <p><strong>日付:</strong> {{ $attendance->created_at->format('Y-m-d') }}</p>
    <p><strong>出勤時刻:</strong> {{ $attendance->clock_in ?? 'ー' }}</p>
    <p><strong>退勤時刻:</strong> {{ $attendance->clock_out ?? 'ー' }}</p>
    <p><strong>休憩開始:</strong> {{ $attendance->break_start ?? 'ー' }}</p>
    <p><strong>休憩終了:</strong> {{ $attendance->break_end ?? 'ー' }}</p>

    <a href="{{ route('attendance.index') }}">一覧に戻る</a>
@endsection