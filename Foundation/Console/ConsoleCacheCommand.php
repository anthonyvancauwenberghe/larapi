<?php

namespace Foundation\Console;

use Foundation\Services\CommandRegistrationService;
use Illuminate\Console\Command;

class ConsoleCacheCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'commands:cache';

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
    public function handle(CommandRegistrationService $service)
    {
        $service->cacheCommands();
        $this->info('Commands cached successfully.');
    }
}
