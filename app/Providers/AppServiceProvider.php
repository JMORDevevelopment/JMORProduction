<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Makes $navigation available in layouts.app on every page automatically,
        // so individual controllers (Home, Blog, Service, etc.) don't each need
        // to fetch and pass it manually the way every CI controller method did.
        View::composer('layouts.app', function ($view) {
            $view->with('navigation', Menu::tree());
        });
    }
}