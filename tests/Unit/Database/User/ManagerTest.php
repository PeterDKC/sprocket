<?php

namespace PeterDKC\Sprocket\Tests\Unit\Database\User;

use PeterDKC\Sprocket\Tests\TestCase;
use PeterDKC\Sprocket\Database\User\Manager;
use PeterDKC\Sprocket\Tests\Unit\Database\User\TestsManager;

class ManagerTest extends TestCase
{
    use TestsManager;

    /** @test */
    public function aNewManagerCanBeConstructed()
    {
        $manager = $this->getDummyManager();

        $this->assertInstanceOf(
            Manager::class,
            $manager
        );
    }

    /** @test */
    public function valuesCanBeSet()
    {
        $manager = $this->getDummyManager();

        $manager->setValues('bob', 'password', 'test');

        $this->assertEquals(
            ['bob', 'password', 'test'],
            $manager->getValues()
        );
    }
}
