<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!defined('EVENT_ORGANIZER')) {
            define('EVENT_ORGANIZER', 3);
        }
        if (!defined('FINANCE_MANAGER')) {
            define('FINANCE_MANAGER', 4);
        }
        if (!defined('BUYER')) {
            define('BUYER', 5);
        }
    }
}
