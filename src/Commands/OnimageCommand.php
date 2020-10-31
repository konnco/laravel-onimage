<?php

namespace Konnco\Onimage\Commands;

use Illuminate\Console\Command;

class OnimageCommand extends Command
{
    public $signature = 'laravel-onimage';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
