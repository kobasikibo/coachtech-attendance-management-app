<?php

namespace App\Providers;

use Laravel\Fortify\Fortify;
use Illuminate\Support\ServiceProvider;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CustomLogoutResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Support\Facades\Auth;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::createUsersUsing(CreateNewUser::class);


        Fortify::loginView(function (Request $request) {
            if ($request->is('admin/*')) {
                return view('auth.admin-login');
            }
            return view('auth.login');
        });

        Fortify::authenticateUsing(function (Request $request) {
            if ($request->is('admin/*')) {
                $admin = \App\Models\Admin::where('email', $request->email)->first();

                if ($admin && Hash::check($request->password, $admin->password)) {
                    return $admin;
                }
            } else {
                $user = \App\Models\User::where('email', $request->email)->first();

                if ($user && Hash::check($request->password, $user->password)) {
                    return $user;
                }
            }
            return null;
        });

        // ログアウトのカスタムレスポンス（共通）
        $this->app->singleton(LogoutResponse::class, CustomLogoutResponse::class);
    }
}