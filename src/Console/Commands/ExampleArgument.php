<?php

namespace PeterDKC\Sprocket\Console\Commands;

use Illuminate\Console\Command;

class ExampleArgument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'example:argument
        {--a|name= : The name to output.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output a name using an argument.';

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
        $this->info($this->option('name'));
    }
}
