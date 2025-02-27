@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}" />
<link rel="stylesheet" href="{{ asset('css/admin-attendance-index.css') }}" />
@endsection

@section('content')
<h1>スタッフ一覧</h1>

<table class="attendance-table">
    <thead>
        <tr>
            <th class="table-header">名前</th>
            <th class="table-header">メールアドレス</th>
            <th class="table-header">詳細</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="table-cell">{{ $user->name }}</td>
            <td class="table-cell">{{ $user->email }}</td>
            <td class="table-cell">
                <a href="{{ route('admin.attendance-staff') }}" class="link-attendance-list">詳細</a>
            </td>
        </tr>
    </tbody>
</table>
@endsection