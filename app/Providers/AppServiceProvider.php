<?php

namespace App\Providers;

use App\Models\TimeLog;
use App\Policies\TimeLogPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Http\Repository\TimeLogRepository;

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
        
        Gate::policy(TimeLog::class, TimeLogPolicy::class);
        $this->app->bind(TimeLogRepository::class);


    }
}
