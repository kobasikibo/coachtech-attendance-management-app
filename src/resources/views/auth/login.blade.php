@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/form-styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')
    <h1>ログイン</h1>

    @if(session('message'))
        <div class="error">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="form-group">
            <div class="email-label">メールアドレス</div>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>

            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="password-label">パスワード</div>
            <input type="password" name="password" class="form-input" required>

            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-submit">ログインする</button>
    </form>

    <a href="{{ route('register') }}" class="auth-link">会員登録はこちら</a>
@endsection