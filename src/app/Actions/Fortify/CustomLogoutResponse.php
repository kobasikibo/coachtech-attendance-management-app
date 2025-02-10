<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomLogoutResponse implements LogoutResponse
{
    public function toResponse($request)
    {
        // ユーザーが管理者かどうかを判定
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect('/admin/login');
        }

        // 一般ユーザーの場合は /login へリダイレクト
        return $request->wantsJson()
            ? new JsonResponse(['message' => 'Logged out'], 204)
            : redirect('/login');
    }
}
