<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        // The model class to the policy class
        'App\Models\Video' => 'App\Policies\VideoPolicy',
    ];


    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
