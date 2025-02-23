<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'coachtech 勤怠管理アプリ')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-container">
                <a class="header-logo" href="/attendance">
                    <img src="{{ asset('images/logo.svg') }}" alt="coachtechのロゴ">
                </a>
            </div>

            @if (!in_array(request()->route()->getName(), ['login', 'register', 'admin.login']))
            <div class="header-container">
                <div class="header-links">
                    <a href="{{ route('attendance.show') }}" class="link-attendance">勤怠</a>
                    <a href="{{ route('attendance.index') }}" class="link-attendance-list">勤怠一覧</a>
                    <a href="{{ route('user.stamp_correction_request.index') }}" class="link-request">申請</a>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="link-style-button">ログアウト</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </header>

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    @yield('scripts')
</body>

</html>