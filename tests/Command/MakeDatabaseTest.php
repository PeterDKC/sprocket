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
    public function true()
    {
        $this->assertTrue(true);
    }
}
