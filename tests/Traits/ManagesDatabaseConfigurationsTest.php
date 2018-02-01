<?php

namespace PeterDKC\Sprocket\Tests\Traits;

use PeterDKC\Sprocket\Tests\TestCase;
use PeterDKC\Sprocket\Traits\ManagesDatabaseConfigurations;

class ManagesDatabaseConfigurationsTest extends TestCase
{
    /**
     * @inheritDoc
     */
    public function setUp()
    {
        parent::setUp();

        // ensure that defaults are as expected
        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.port' => 3306]);
    }

    /** @test */
    public function traitCanReturnValues()
    {
        $this->assertEquals(
            3306,
            $this->getDummy()->getValue('port')
        );
    }

    /** @test */
    public function traitHasDriver()
    {
        $this->assertEquals(
            'mysql',
            $this->getDummy()->getProp('driver')
        );
    }

    /** @test */
    public function traitHasPrefix()
    {
        $this->assertEquals(
            'database.connections.mysql.',
            $this->getDummy()->getProp('prefix')
        );
    }

    /**
     * Get a dummy class that uses the trait.
     *
     * @return stdClass
     */
    protected function getDummy()
    {
        return new class {
            use ManagesDatabaseConfigurations;

            public function getProp(string $prop)
            {
                return $this->$prop;
            }

            public function getValue(string $value)
            {
                return $this->value($value);
            }
        };
    }
}
