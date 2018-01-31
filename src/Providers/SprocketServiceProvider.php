<?php

namespace PeterDKC\Sprocket\Providers;

use Illuminate\Support\ServiceProvider;
use PeterDKC\Sprocket\Commands\MakeDatabase;

class SprocketServiceProvider extends ServiceProvider
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
