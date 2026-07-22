<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        View::composer(
            ['layouts.navigation', 'layouts.partials.notification-dropdown', 'layouts.sidebar'],
            function ($view) {
                if (! auth()->check()) {
                    return;
                }

                $user = auth()->user();

                $view->with('unreadNotificationCount', $user->unreadNotifications()->count());
                $view->with('recentNotifications', $user->unreadNotifications()->latest()->limit(5)->get());
            }
        );
    }
}
