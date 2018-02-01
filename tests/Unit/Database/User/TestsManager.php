<?php

namespace PeterDKC\Sprocket\Tests\Unit\Database\User;

use PeterDKC\Sprocket\Commands\MakeDatabase;
use PeterDKC\Sprocket\Database\User\Manager;

trait TestsManager
{
    /**
     * A command instance.
     *
     * @var \PeterDKC\Sprocket\Commands\MakeDatabase
     */
    protected $command;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        parent::setUp();

        // ensure that defaults are as expected
        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.host' => 'localhost']);

        $this->command = app(MakeDatabase::class);
    }

    /**
     * Get a Dummy that extends the abstract.
     *
     * @return stdClass
     */
    protected function getDummyManager()
    {
        return new class($this->command) extends Manager {
            /**
             * Get the values for testing.
             *
             * @return array
             */
            public function getValues()
            {
                return [
                    $this->username,
                    $this->password,
                    $this->database,
                ];
            }

            public function createUser()
            {
                //
            }

            public function dropUser()
            {
                //
            }

            protected function grantPrivileges()
            {
                //
            }
        };
    }
}
