<?php

namespace Foundation\Providers;

use Foundation\Console\ConsoleCacheCommand;
use Foundation\Console\GetConsolesCommand;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class CommandServiceProvider extends ServiceProvider
{
    protected $commands = [
        ConsoleCacheCommand::class,
        GetConsolesCommand::class
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
        /*if (env('APP_ENV') !== 'production')
            Artisan::call('commands:cache');*/

        /*Artisan::call('commands:get');

        $commands = Artisan::output();
        $this->commands(json_decode($commands));*/
    }


}
