<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
        Auth::provider('admins', function ($app, array $config) {
            return new \Illuminate\Auth\EloquentUserProvider($app['hash'], Admin::class);
        });
    }
}

