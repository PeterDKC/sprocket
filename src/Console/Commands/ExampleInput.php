<?php

namespace PeterDKC\Sprocket\Console\Commands;

use Illuminate\Console\Command;

class ExampleInput extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'example:input';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output a name based on interactive input.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info($this->ask('What\'s your name boss?'));
    }
}
