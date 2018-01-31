<?php

namespace Pdemarco\LaravelUtils\Commands;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
    /**
     * Output a formatted console line.
     *
     * @return void
     */
    public function rule()
    {
        $this->info(console_line());
    }
}
