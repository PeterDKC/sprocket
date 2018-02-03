<?php

namespace Tests\Command;

use PeterDKC\Sprocket\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;

class ExampleTest extends TestCase
{
    /** @test */
    public function testsSimpleExampleCommand()
    {
        Artisan::call('example:simple');

        $this->assertEquals(
            "bob\n",
            Artisan::output()
        );
    }

    /** @test */
    public function testsArgumentExampleCommand()
    {
        Artisan::call('example:argument', [
            '--name' => 'bob'
        ]);

        $this->assertEquals(
            "bob\n",
            Artisan::output()
        );
    }

    /** @test */
    public function testsInputExampleCommand()
    {
        // setup the application and add the command
        $application = app()
            ->makeWith(\Illuminate\Console\Application::class, [
                'version' => $this->app::VERSION
            ]);

        $application->add(
            $application->resolve(
                'PeterDKC\Sprocket\Console\Commands\ExampleInput'
            )
        );

        // create a CommandTester based on the command
        $command = $application->find('example:input');

        $tester = new CommandTester($command);

        $tester->setInputs(['bob']);

        $tester->execute(['command' => $command->getName()]);

        // set the expected output and assert on it
        $expected = "\n" .
            " What's your name boss?:\n" .
            " > \n" .
            "bob\n";

        $this->assertEquals(
            $expected,
            $tester->getDisplay()
        );
    }
}
