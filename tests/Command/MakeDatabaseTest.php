<?php

namespace PeterDKC\Sprocket\Tests\Command;

use Exception;
use PDOException;
use Mockery as m;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Application;
use PeterDKC\Sprocket\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PeterDKC\Sprocket\Commands\MakeDatabase;
use Symfony\Component\Console\Tester\CommandTester;

class MakeDatabaseTest extends TestCase
{
    /** @test */
    public function sqliteDoesNotNeedAUser()
    {
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => 'db.sqlite',
        ]);

        DB::shouldReceive('getDoctrineSchemaManager')
            ->andReturnSelf();

        DB::shouldReceive('dropAndCreateDatabase');

        DB::shouldReceive('listDatabases')
            ->andReturn(['db.sqlite']);

        DB::shouldReceive('statement');

        Artisan::call('sprocket:makedb', [
            '--username' => 'bob',
            '--password' => 'password',
        ]);

        $expected = "--------------------\n" .
            "Creates a local development databse and user based on .env values.\n" .
            "--------------------\n" .
            "Sqlite Database configured.\n" .
            "Skipping credentials.\n" .
            "Created Database db.sqlite\n" .
            "Current driver does not require a user.\n";

        $this->assertEquals(
            $expected,
            Artisan::output()
        );
    }

    /** @test */
    public function usersAreCreated()
    {
        config([
            'database.default' => 'mysql',
        ]);

        DB::shouldReceive('getDoctrineSchemaManager')
            ->andReturnSelf();

        DB::shouldReceive('dropAndCreateDatabase');

        DB::shouldReceive('listDatabases')
            ->andReturn(['forge']);

        DB::shouldReceive('statement');

        Artisan::call('sprocket:makedb', [
            '--username' => 'bob',
            '--password' => 'password',
        ]);

        $expected = "--------------------\n" .
            "Creates a local development databse and user based on .env values.\n" .
            "--------------------\n" .
            "Created Database forge\n" .
            "Created User forge\n";

        $this->assertEquals(
            $expected,
            Artisan::output()
        );
    }

    /** @test */
    public function failedDatabaseCreationThrowsError()
    {
        config([
            'database.default' => 'mysql',
        ]);

        DB::shouldReceive('getDoctrineSchemaManager')
            ->andReturnSelf();

        DB::shouldReceive('dropAndCreateDatabase');

        DB::shouldReceive('listDatabases')
            ->andReturn([]);

        DB::shouldReceive('statement');

        Artisan::call('sprocket:makedb', [
            '--username' => 'bob',
            '--password' => 'password',
        ]);

        $expected = "--------------------\n" .
            "Creates a local development databse and user based on .env values.\n" .
            "--------------------\n" .
            "ERROR! Couldn't create database forge.\n";

        $this->assertEquals(
            $expected,
            Artisan::output()
        );
    }

    /** @test */
    public function exceptionsAreHandled()
    {
        DB::shouldReceive('getDoctrineSchemaManager')
            ->andThrow(new PDOException('ERROR! Something bad happened!'));

        Artisan::call('sprocket:makedb', [
            '--username' => 'bob',
            '--password' => 'password',
        ]);

        $expected = "--------------------\n" .
            "Creates a local development databse and user based on .env values.\n" .
            "--------------------\n" .
            "ERROR! Something bad happened!\n";

        $this->assertEquals(
            $expected,
            Artisan::output()
        );

        DB::shouldReceive('getDoctrineSchemaManager')
            ->andThrow(new Exception('ERROR! Something bad happened!'));

        Artisan::call('sprocket:makedb', [
            '--username' => 'bob',
            '--password' => 'password',
        ]);

        $expected = "--------------------\n" .
            "Creates a local development databse and user based on .env values.\n" .
            "--------------------\n" .
            "ERROR! Something bad happened!\n";

        $this->assertEquals(
            $expected,
            Artisan::output()
        );
    }

    /** @test */
    public function databasesCanBeTornDown()
    {
        config([
            'database.default' => 'mysql',
        ]);

        DB::shouldReceive('getDoctrineSchemaManager')
            ->andReturnSelf();

        DB::shouldReceive('dropDatabase')->with('forge');

        DB::shouldReceive('listDatabases')
            ->andReturn(['forge'], []);

        DB::shouldReceive('statement');

        Artisan::call('sprocket:makedb', [
            '--teardown' => true,
            '--username' => 'bob',
            '--password' => 'password',
        ]);

        $expected = "--------------------\n" .
            "Creates a local development databse and user based on .env values.\n" .
            "--------------------\n" .
            "Dropped User forge\n" .
            "Dropped Database forge\n";

        $this->assertEquals(
            $expected,
            Artisan::output()
        );
    }

    /** @test */
    public function exceptionIfDatabaseNotTornDown()
    {
        config([
            'database.default' => 'mysql',
        ]);

        DB::shouldReceive('getDoctrineSchemaManager')
            ->andReturnSelf();

        DB::shouldReceive('dropDatabase')->with('forge');

        DB::shouldReceive('listDatabases')
            ->andReturn(['forge'], ['forge']);

        DB::shouldReceive('statement');

        Artisan::call('sprocket:makedb', [
            '--teardown' => true,
            '--username' => 'bob',
            '--password' => 'password',
        ]);

        $expected = "--------------------\n" .
            "Creates a local development databse and user based on .env values.\n" .
            "--------------------\n" .
            "Dropped User forge\n" .
            "ERROR! Could not remove forge\n";

        $this->assertEquals(
            $expected,
            Artisan::output()
        );
    }

    /** @test */
    public function noUserDroppedForSqlite()
    {
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => 'db.sqlite',
        ]);

        DB::shouldReceive('getDoctrineSchemaManager')
            ->andReturnSelf();

        DB::shouldReceive('dropDatabase')->with('db.sqlite');

        DB::shouldReceive('listDatabases')
            ->andReturn(['db.sqlite'], []);

        DB::shouldReceive('statement');

        Artisan::call('sprocket:makedb', [
            '--teardown' => true,
        ]);

        $expected = "--------------------\n" .
            "Creates a local development databse and user based on .env values.\n" .
            "--------------------\n" .
            "Sqlite Database configured.\n" .
            "Skipping credentials.\n" .
            "No User for this driver type.\n" .
            "Dropped Database db.sqlite\n";

        $this->assertEquals(
            $expected,
            Artisan::output()
        );
    }
}
