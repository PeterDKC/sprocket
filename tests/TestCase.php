<?php

namespace Pdemarco\LaravelUtils\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Pdemarco\LaravelUtils\Providers\UtilsServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * Get the package Service Providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            UtilsServiceProvider::class,
        ];
    }
}
