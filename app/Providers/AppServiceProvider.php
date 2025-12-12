<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set default string length for MySQL
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
    }
}
