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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pagination default memakai Bootstrap 5 (bukan Tailwind),
        // supaya tombol next/prev tidak membesar.
        Paginator::useBootstrapFive();
    }
}
