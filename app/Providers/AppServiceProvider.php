<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Check whether user roles is manager
        Gate::define('isManager',function ($user){
            return $user->role === 'manager';
        });
        Gate::define('isEmployee',function($user){
            return $user->role === 'employee';
        });
    
    }
}
