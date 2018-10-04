<?php

namespace Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GetConsolesCommand extends Command
{
    protected $files;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'commands:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns a list of all available command classes in the Modules namespace.';

    protected $moduleCommandDirectoryNames = [
        'console',
        'commands'
    ];

    /**
     * Create a new route command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commandCachePath = $this->laravel->bootstrapPath() . '/cache/commands.json';

        if (!file_exists($commandCachePath))
            throw new \Exception("Command cache file not found");

        $commands = $this->files->get($commandCachePath);
        $this->info($commands);
    }
}
