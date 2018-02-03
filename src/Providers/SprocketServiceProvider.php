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
            \PeterDKC\Sprocket\Console\Commands\ExampleSimple::class,
            \PeterDKC\Sprocket\Console\Commands\ExampleArgument::class,
            \PeterDKC\Sprocket\Console\Commands\ExampleInput::class,
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
