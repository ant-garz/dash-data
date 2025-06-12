<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });


        RateLimiter::for('login', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
