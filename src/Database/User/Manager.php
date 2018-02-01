<?php

namespace PeterDKC\Sprocket\Database\User;

use PeterDKC\Sprocket\Commands\MakeDatabase;
use PeterDKC\Sprocket\Traits\ManagesDatabaseConfigurations;

abstract class Manager
{
    use ManagesDatabaseConfigurations;

    /**
     * The MakeDatabase Command.
     *
     * @var \PeterDKC\Sprocket\Commands\MakeDatabase
     */
    protected $command;

    abstract public function createUser();
    abstract public function dropUser();
    abstract protected function grantPrivileges();

    /**
     * Constructs a manager instance.
     *
     * @param \PeterDKC\Sprocket\Commands\MakeDatabase $command
     */
    public function __construct(MakeDatabase $command)
    {
        $this->command = $command;

        $this->host = $this->value('host');
    }

    /**
     * Because the config has been overwritten in runtime,
     * we need to set these manually from the Mangler.
     *
     * @param string $username
     * @param string $password
     * @param string $database
     */
    public function setValues(string $username, string $password, string $database)
    {
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        return $this;
    }
}
