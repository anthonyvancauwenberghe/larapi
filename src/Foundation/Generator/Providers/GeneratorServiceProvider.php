<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 18:23
 */

namespace Foundation\Generator\Providers;


use Foundation\Generator\Commands\EventMakeCommand;
use Foundation\Generator\Commands\CommandMakeCommand;
use Foundation\Generator\Commands\ControllerMakeCommand;
use Foundation\Generator\Commands\FactoryMakeCommand;
use Foundation\Generator\Commands\JobMakeCommand;
use Foundation\Generator\Commands\ListenerMakeCommand;
use Illuminate\Support\ServiceProvider;


class GeneratorServiceProvider extends ServiceProvider
{

    protected $commands = [
        FactoryMakeCommand::class,
        CommandMakeCommand::class,
        ControllerMakeCommand::class,
        EventMakeCommand::class,
        JobMakeCommand::class,
        ListenerMakeCommand::class
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}
