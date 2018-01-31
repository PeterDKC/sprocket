<?php

namespace Pdemarco\LaravelUtils\Providers;

use Illuminate\Support\ServiceProvider;
use Pdemarco\LaravelUtils\Commands\MakeDatabase;

class UtilsServiceProvider extends ServiceProvider
{
    /**
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            MakeDatabase::class,
        ]);
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
