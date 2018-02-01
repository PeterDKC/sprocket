<?php

namespace PeterDKC\Sprocket\Traits;

trait ManagesDatabaseConfigurations
{
    /**
     * The MySQL host.
     *
     * @var string
     */
    protected $host;

    /**
     * The local database to create.
     *
     * @var string
     */
    protected $database;

    /**
     * The password to give the created user.
     *
     * @var string
     */
    protected $password;

    /**
     * The user to create.
     *
     * @var string
     */
    protected $username;

    /**
     * Get any undefined properties.
     *
     * @param  string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        if ($property == 'driver') {
            return config('database.default');
        }

        if ($property == 'prefix') {
            return 'database.connections.' .
                config('database.default') .
                '.';
        }
    }

    /**
     * Get the item from config.
     *
     * @param  string $item
     * @return string
     */
    protected function value(string $item)
    {
        return config($this->prefix . $item);
    }
}
