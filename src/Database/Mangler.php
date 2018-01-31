<?php

namespace PeterDKC\Sprocket\Database;

use Exception;
use Illuminate\Support\Facades\DB;

class Mangler
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
     * The prefix of the mysql configuration items.
     *
     * @var string
     */
    protected $prefix = 'database.connections.mysql.';

    /**
     * The user to create.
     *
     * @var string
     */
    protected $username;

    /**
     * The DBal Schema Manager.
     *
     * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    protected $manager;

    /**
     * Contruct a new instance of the Mangler.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->database = $this->value('database');
        $this->user = $this->value('username');
        $this->password = $this->value('password');

        $this->overwrite($username, $password);

        $this->manager = DB::getDoctrineSchemaManager();
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

        // echo 'Created database ' . $this->database . "\n";

        // if (! $this->createUser()) {
        //     throw new Exception('Couldn\'t create user ' . $this->userWithHost());
        // }

        // echo 'Created user ' . $this->userWithHost() . "\n";

        // if (! $this->grantPrivileges()) {
        //     throw new Exception('Couldn\'t grant user privileges.');
        // }

        // echo 'Granted priveleges on ' . $this->database . "\n";
    }

    /**
     * Tear down the configured user and database.
     *
     * @return void
     */
    public function tearDown()
    {
        if (! $this->dropUser()) {
            throw new Exception('Couldn\'t drop user ' . $this->userWithHost());
        }

        echo 'Dropped user ' . $this->userWithHost() . "\n";

        if (! $this->flush()) {
            throw new Exception('Couldn\t flush privileges.');
        }

        echo 'Flushed MySQL Privileges.' . "\n";

        if (! $this->dropDatabase()) {
            throw new Exception('Couldn\'t drop database ' . $this->database);
        }

        echo 'Dropped Database ' . $this->database . "\n";
    }

    /**
     * Create the database if not exists.
     *
     * @return bool
     */
    protected function createDatabase()
    {
        $this->manager->dropAndCreateDatabase($this->database);

        if (! collect($this->manager->listDatabases())->contains($this->database)) {
            throw new Exception("Couldn't create {$this->database}.");
        }
    }

    /**
     * Create the dev user.
     *
     * @return bool
     */
    protected function createUser()
    {
        $localPassword = $this->value('password');

        return DB::statement(
            'CREATE USER IF NOT EXISTS ' .
            $this->userWithHost() . ' ' .
            "IDENTIFIED BY '{$localPassword}'"
        );
    }

    /**
     * Drop the configured database.
     *
     * @return bool
     */
    protected function dropDatabase()
    {
        return DB::statement(
            'DROP DATABASE IF EXISTS ' . $this->database
        );
    }

    /**
     * Drop the User.
     *
     * @return bool
     */
    protected function dropUser()
    {
        return DB::statement(
            'DROP USER IF EXISTS ' . $this->userWithHost()
        );
    }

    /**
     * Flush the MySQL Privileges.
     *
     * @return bool
     */
    protected function flush()
    {
        return DB::statement(
            'FLUSH PRIVILEGES'
        );
    }

    /**
     * Grant Priveleges to the created User.
     *
     * @return bool
     */
    protected function grantPrivileges()
    {
        return DB::statement(
            'GRANT ALL PRIVILEGES ON ' . $this->database . '.* ' .
            'TO ' . $this->userWithHost()
        );
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
        config([$this->prefix . 'database' => '']);
        config([$this->prefix . 'username' => $username]);
        config([$this->prefix . 'password' => $password]);
    }

    /**
     * get the User + Host value.
     *
     * @return string
     */
    protected function userWithHost()
    {
        return implode('', [
            '\'',
            $this->value('username'),
            '\'@\'',
            $this->host,
            '\''
        ]);
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
