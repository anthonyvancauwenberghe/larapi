<?php

namespace Foundation\Console;

use Foundation\Services\BootstrapRegistrarService;
use Illuminate\Console\Command;

class BootstrapCacheCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'bootstrap:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache all available command classes in the Modules namespace.';

    protected $moduleCommandDirectoryNames = [
        'console',
        'commands',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(BootstrapRegistrarService $service)
    {
        $service->cache();
        $this->info('Commands cached successfully.');
    }
}
