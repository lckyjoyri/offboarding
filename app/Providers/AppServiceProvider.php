<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

use App\Models\ClearanceRequest;

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
        Gate::define('access-hr', function ($user) {
            return $user->role_id === 1;
        });

        Gate::define('access-official', function ($user) {
            return $user->role_id === 2;
        });

        Gate::define('access-home', function ($user) {
            return $user->role_id === 3;
        });

        View::composer('*', function ($view) {
            $count_new_requests = ClearanceRequest::where('status', 1)->count();
            $view->with('count_new_requests', $count_new_requests);
        });

        View::composer('*', function ($view) {
            $count_completed_requests = ClearanceRequest::where('status', 5)->whereNull('generated_coe_path')->count();
            $view->with('count_completed_requests', $count_completed_requests);
        });

        View::composer('*', function ($view) {
            $userId = Auth::id();
        
            $coe_available = ClearanceRequest::where('status', 5)
                ->whereNotNull('generated_coe_path')
                ->where('user_id', $userId)
                ->count();
        
            $view->with('coe_available', $coe_available);
        });
    }
}
