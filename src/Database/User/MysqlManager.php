<?php

namespace PeterDKC\Sprocket\Database\User;

use Illuminate\Support\Facades\DB;
use PeterDKC\Sprocket\Database\User\Manager;

class MysqlManager extends Manager
{
    /**
     * Create the user.
     *
     * @return void
     */
    public function createUser()
    {
        DB::statement(
            'CREATE USER IF NOT EXISTS ' .
            "'{$this->username}'@'{$this->host}' " .
            'IDENTIFIED BY ' .
            "'{$this->password}'"
        );

        $this->grantPrivileges();
    }

    /**
     * Drop the configured User.
     *
     * @return void
     */
    public function dropUser()
    {
        DB::statement(
            'DROP USER IF EXISTS ' .
            "'{$this->username}'@'{$this->host}'"
        );

        $this->flush();
    }

    /**
     * FLUSH the MySQL Privileges.
     *
     * @return void
     */
    protected function flush()
    {
        DB::statement('FLUSH PRIVILEGES');
    }

    /**
     * Grant the user privileges.
     *
     * @return void
     */
    protected function grantPrivileges()
    {
        DB::statement(
            'GRANT ALL ON '.
            "$this->database.* " .
            'TO ' .
            "'{$this->username}'@'{$this->host}'"
        );
    }
}
