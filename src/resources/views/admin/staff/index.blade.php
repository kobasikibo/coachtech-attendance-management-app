@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-staff-list.css') }}" />
@endsection

@section('content')
<h1>スタッフ一覧</h1>

<table class="staff-table">
    <thead>
        <tr>
            <th class="table-header">名前</th>
            <th class="table-header">メールアドレス</th>
            <th class="table-header">月次勤怠</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td class="table-cell">{{ $user->name }}</td>
            <td class="table-cell">{{ $user->email }}</td>
            <td class="table-cell">
                <a href="{{ route('admin.attendance.staff', ['id' => $user->id, 'month' => now()->format('Y-m')]) }}" class="link-attendance-monthly">詳細</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection