<?php

namespace App\Providers;

use App\Auth\MD5EloquentUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Auth::provider('md5-eloquent', function ($app, array $config) {
            return new MD5EloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
