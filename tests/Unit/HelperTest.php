<?php

namespace PeterDKC\Sprocket\Tests\Unit;

use PeterDKC\Sprocket\Tests\TestCase;

class ClassName extends TestCase
{
    /** @test */
    public function consoleLineDefault()
    {
        $this->assertEquals(
            '--------------------',
            console_line()
        );
    }

    /** @test */
    public function consoleLineTakesLength()
    {
        $this->assertEquals(
            '----',
            console_line(4)
        );
    }
}
