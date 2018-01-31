<?php

namespace PeterDKC\Sprocket\Tests\Command;

use Mockery as m;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Console\Kernel;
use PeterDKC\Sprocket\Tests\TestCase;
use PeterDKC\Sprocket\Commands\MakeDatabase;

class MakeDatabaseTest extends TestCase
{
    /** @test */
    public function testsAreUp()
    {
        $command = m::mock(
            MakeDatabase::class
        );

        $command->shouldReceive('anticipate')
            ->once()
            ->with(
                'Please enter your local priveleged database user',
                ['root', 'homestead', 'laravel']
            )->andReturn('root');

        $command->shouldReceive('secret')
            ->once()
            ->with('Please enter the password for the priveleged user')
            ->andReturn('password');

        app(Kernel::class)->registerCommand($command);

        $this->artisan('lu:make-database', ['--no-interaction' => true]);
    }
}
