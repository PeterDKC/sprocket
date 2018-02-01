<?php

namespace PeterDKC\Sprocket\Unit\Database\User;

use Illuminate\Support\Facades\DB;
use PeterDKC\Sprocket\Tests\TestCase;
use PeterDKC\Sprocket\Database\User\MysqlManager;
use PeterDKC\Sprocket\Tests\Unit\Database\User\TestsManager;

class MysqlManagerTest extends TestCase
{
    use TestsManager;

    /** @test */
    public function usersCanBeCreated()
    {
        DB::shouldReceive('statement')
            ->with(
                'GRANT ALL ON forge.* ' .
                'TO ' .
                "'forge'@'localhost'"
            );

        DB::shouldReceive('statement')
            ->with(
                'CREATE USER IF NOT EXISTS ' .
                "'forge'@'localhost' " .
                'IDENTIFIED BY ' .
                "'password'"
            );

        app()->makeWith(
            MysqlManager::class,
            ['command' => $this->command]
        )->setValues('forge', 'password', 'forge')
        ->createUser();
    }

    /** @test */
    public function userCanBeDropped()
    {
        DB::shouldReceive('statement')
            ->with(
                'FLUSH PRIVILEGES'
            );

        DB::shouldReceive('statement')
            ->with(
                'DROP USER IF EXISTS ' .
                "'forge'@'localhost'"
            );

        app()->makeWith(
            MysqlManager::class,
            ['command' => $this->command]
        )->setValues('forge', 'password', 'forge')
        ->dropUser();
    }
}
