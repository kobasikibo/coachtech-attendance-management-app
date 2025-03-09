@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-index.css') }}" />
<link rel="stylesheet" href="{{ asset('css/stamp_correction_request.css') }}" />
@endsection

@section('content')
<h1>申請一覧</h1>

<div class="tab-container">
    <a href="{{ url('/admin/stamp_correction_request/list?tab=pending') }}" class="tab-link {{ $tab === 'pending' ? 'active' : '' }}">承認待ち</a>
    <a href="{{ url('/admin/stamp_correction_request/list?tab=approved') }}" class="tab-link {{ $tab === 'approved' ? 'active' : '' }}">承認済み</a>
</div>

<table class="attendance-table">
    <thead>
        <tr>
            <th class="table-header">状態</th>
            <th class="table-header">名前</th>
            <th class="table-header">対象日時</th>
            <th class="table-header">申請理由</th>
            <th class="table-header">申請日時</th>
            <th class="table-header">詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach($correctionRequests as $correction)
        <tr>
            <td class="table-cell">{{ $correction->status }}</td>
            <td class="table-cell">{{ $correction->attendance->user->name }}</td>
            <td class="table-cell">{{ \Carbon\Carbon::parse($correction->attendance->date)->translatedFormat('Y/m/d') }}</td>
            <td class="table-cell">{{ $correction->remarks }}</td>
            <td class="table-cell">{{ \Carbon\Carbon::parse($correction->updated_at)->translatedFormat('Y/m/d') }}</td>
            <td class="table-cell">
                <a class="table-link" href="{{ route('admin.stamp_correction_request.show', $correction->id) }}">詳細</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('scripts')
<script src="{{ asset('js/admin-tab-selector.js') }}" defer></script>
@endsection