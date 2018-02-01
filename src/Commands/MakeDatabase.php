<?php

namespace PeterDKC\Sprocket\Commands;

use Exception;
use PeterDKC\Sprocket\Database\Mangler;
use PeterDKC\Sprocket\Commands\BaseCommand;

class MakeDatabase extends BaseCommand
{
    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates or tears down a local development Database and User.';

    /**
     * An instance of DatabaseMangler.
     *
     * @var \PeterDKC\Sprocket\DatabaseMangler
     */
    protected $mangler;

    /**
     * The local priveleged database user.
     *
     * @var string
     */
    protected $user = '';

    /**
     * The local user's password.
     *
     * @var string
     */
    protected $password = '';

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'sprocket:makedb
        {--t|teardown : Tear down the database and user in your .env}';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->intro()
            ->getPrivelegedCredentials();

        $this->mangler = new Mangler(
            $this->user,
            $this->password,
            $this
        );

        if ($this->option('teardown')) {
            $this->tearDown();

            return;
        }

        $this->makeDatabase();
    }

    /**
     * Get the Priveleged Credentials to create our DB and User.
     *
     * @return $this
     */
    protected function getPrivelegedCredentials()
    {
        if (config('database.default') === 'sqlite') {
            $this->comment('Sqlite Database configured.');
            $this->comment('Skipping credentials.');

            return $this;
        }

        $this->comment(
            'We need a login that can create / delete other users and ' .
            'their associated databases on your local MySQL instance.'
        );
        $this->comment('This is generally the `root` user.');

        $this->rule();

        $this->user = $this->anticipate(
            'Please enter your local priveleged database user',
            ['root', 'homestead', 'laravel']
        );

        $this->password = $this->secret('Please enter the password for the priveleged user');

        return $this;
    }

    /**
     * Output the intro text.
     *
     * @return $this
     */
    protected function intro()
    {
        $this->rule();
        $this->info('Creates a local development databse and user based on .env values.');
        $this->rule();

        return $this;
    }

    /**
     * Make the configured database and user.
     *
     * @return void
     */
    protected function makeDatabase()
    {
        try {
            $this->mangler->makeDatabase();
        } catch (Exception $exception) {
            throw $exception;
            // $this->error($exception->getMessage());

            exit;
        }
    }

    /**
     * Tear Down the configured database and user.
     *
     * @return void
     */
    protected function tearDown()
    {
        try {
            $this->mangler->tearDown();
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            exit;
        }
    }
}
