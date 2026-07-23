<?php

namespace App\Providers;

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
        // Trik Vercel: Arahkan folder penyimpanan view ke /tmp
        if (isset($_ENV['VERCEL']) || env('VERCEL')) {
            config(['view.compiled' => '/tmp']);
        }
    }
}