<?php

namespace PeterDKC\Sprocket\Tests\Unit;

use Illuminate\Support\Facades\DB;
use PeterDKC\Sprocket\Tests\TestCase;
use PeterDKC\Sprocket\Database\Mangler;
use PeterDKC\Sprocket\Commands\MakeDatabase;

class ManglerTest extends TestCase
{
    /** @test */
    public function configIsOverwritten()
    {
        DB::shouldReceive('getDoctrineSchemaManager');

        config(['database.default' => 'mysql']);

        $command = app(MakeDatabase::class);

        $config = config('database.connections.mysql');

        $this->assertEquals(
            ['forge', 'forge', ''],
            [$config['database'], $config['username'], $config['password']]
        );

        new Mangler('bob', 'password', $command);

        $config = config('database.connections.mysql');

        $this->assertEquals(
            ['', 'bob', 'password'],
            [$config['database'], $config['username'], $config['password']]
        );
    }
}
