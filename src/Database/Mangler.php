<?php

namespace PeterDKC\Sprocket\Database;

use Exception;
use Illuminate\Support\Facades\DB;
use PeterDKC\Sprocket\Database\User\Manager;
use PeterDKC\Sprocket\Traits\ManagesDatabaseConfigurations;

class Mangler
{
    use ManagesDatabaseConfigurations;

    /**
     * The DBal Schema Manager.
     *
     * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    protected $dbManager;

    /**
     * The command that called us.
     *
     * @var \PeterDKC\Sprocket\Database\Mangler
     */
    protected $command;


    /**
     * The drivers that require a User.
     *
     * @var array
     */
    protected $needsUser = [
        'mysql',
        'postgres',
        'sqlsrv'
    ];

    /**
     * Contruct a new instance of the Mangler.
     *
     * @param string $username
     * @param string $password
     * @param \PeterDKC\Sprocket\Database\Mangler $command
     */
    public function __construct(string $username, string $password, $command)
    {
        $this->command = $command;

        $this->overwrite($username, $password);

        $this->dbManager = DB::getDoctrineSchemaManager();
    }

    /**
     * Create a local Database and User identified by Password.
     *
     * @param  string $username
     * @param  string $password
     * @return void
     */
    public function makeDatabase()
    {
        $this->createDatabase();
    }

    /**
     * Tear down the configured user and database.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->dropUser();
    }

    /**
     * Create the database if not exists.
     *
     * @return $this
     */
    protected function createDatabase()
    {
        $this->dbManager->dropAndCreateDatabase($this->database);

        if (! $this->databaseExists()) {
            $this->command->error("ERROR! Couldn't create database {$this->database}.");

            return;
        }

        $this->command->info('Created Database ' . $this->database);

        $this->createUser();
    }

    /**
     * Create the dev user.
     *
     * @return bool
     */
    protected function createUser()
    {
        if (! $this->needsUser()) {
            $this->command->info('Current driver does not require a user.');

            return;
        }

        $this->makeUserManager()
            ->createUser();

        $this->command->info('Created User ' . $this->username);
    }

    /**
     * Check to see if the database exists.
     *
     * @return bool
     */
    protected function databaseExists()
    {
        return collect($this->dbManager->listDatabases())
            ->contains($this->database);
    }

    /**
     * Drop the configured database.
     *
     * @return bool
     */
    protected function dropDatabase()
    {
        if ($this->databaseExists()) {
            $this->dbManager->dropDatabase(
                $this->database
            );
        }

        if ($this->databaseExists()) {
            throw new Exception('ERROR! Could not remove ' . $this->database);
        }

        $this->command->info('Dropped Database ' . $this->database);
    }

    /**
     * Drop the User.
     *
     * @return bool
     */
    protected function dropUser()
    {
        if ($this->needsUser()) {
            $this->makeUserManager()
                ->dropUser();

            $this->command->info('Dropped User ' . $this->username);

            $this->dropDatabase();

            return;
        }

        $this->command->comment('No User for this driver type.');

        $this->dropDatabase();
    }

    /**
     * Make an instance of a User Manager.
     *
     * @return void
     */
    protected function makeUserManager()
    {
        return app()
            ->makeWith(
                $this->requiredManager(),
                ['command' => $this->command]
            )
            ->setValues(
                $this->username,
                $this->password,
                $this->database
            );
    }

    /**
     * See if the current driver requires a User.
     *
     * @return bool
     */
    protected function needsUser()
    {
        return collect($this->needsUser)
            ->contains($this->value('driver'));
    }

    /**
     * Overwrite the .env values in runtime.
     *
     * @param  string $username
     * @param  string $password
     *
     * @return void
     */
    protected function overwrite(string $username, string $password)
    {
        // extract the current values
        $this->database = $this->value('database');
        $this->username = $this->value('username');
        $this->password = $this->value('password');

        // overwrite the config with our privileged values
        config([$this->prefix . 'database' => '']);
        config([$this->prefix . 'username' => $username]);
        config([$this->prefix . 'password' => $password]);
    }

    /**
     * Return a FQCN for the User Manager associated to the driver.
     *
     * @return \PeterDKC\Sprocket\Database\User\Manager
     */
    protected function requiredManager()
    {
        return 'PeterDKC\Sprocket\Database\User\\' .
            ucwords($this->driver) .
            'Manager';
    }
}
