<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();

                Auth::logout();

                return redirect()->route('login')->with('message', 'メール認証が完了していません。確認メールを再送信しました。');
            }

            if ($user->is_first_login) {
                $user->refresh();
                $user->update(['is_first_login' => false]);

                return redirect('/attendance');
            }

            return redirect()->intended('/attendance');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }
}