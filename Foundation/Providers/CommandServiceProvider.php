<?php

namespace Foundation\Providers;

use Foundation\Console\ConsoleCacheCommand;
use Foundation\Services\CommandRegistrationService;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    protected $commands = [
        ConsoleCacheCommand::class
    ];

    /**
     * Register all Module commands.
     *
     * @return void
     */
    public function register()
    {
        $this->loadFoundationCommands();
        $this->loadModuleCommands();
    }

    private function loadFoundationCommands()
    {
        $this->commands($this->commands);
    }

    private function loadModuleCommands()
    {
        $service = new CommandRegistrationService();

        if (env('APP_ENV') !== 'production')
            $service->cacheCommands();

        $this->commands($service->getCommandsFromCache());
    }


}
