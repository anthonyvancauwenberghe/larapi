<?php

namespace Foundation\Providers;

use Foundation\Console\SeedCommand;
use Foundation\Services\BootstrapRegistrarService;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Route;

/**
 * Class BootstrapServiceProvider.
 */
class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * @var BootstrapRegistrarService
     */
    protected $bootstrapService;

    public function register()
    {
        $this->loadBootstrapService();
        $this->loadCommands();
        $this->loadPolicies();
        $this->loadRoutes();
        $this->loadConfigs();
        $this->loadFactories();
        $this->loadMigrations();

        $this->overrideSeedCommand();
    }

    public function loadBootstrapService()
    {
        $this->bootstrapService = new BootstrapRegistrarService();

        if (!$this->app->environment('production')) {
            $this->bootstrapService->recache();
        }
    }

    private function loadCommands()
    {
        $this->commands($this->bootstrapService->getCommands());
    }

    private function loadRoutes()
    {
        foreach ($this->bootstrapService->getRoutes() as $route) {
            $path = $route['path'];
            Route::group([
                'prefix' => 'v1/' . $route['module'],
                'namespace' => $route['controller'],
                'domain' => $route['domain'],
                'middleware' => ['api'],
            ], function (Router $router) use ($path) {
                require $path;
            });
            Route::model($route['module'], $route['model']);
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function loadConfigs()
    {
        foreach ($this->bootstrapService->getConfigs() as $config) {
            $this->publishes([
                $config['path'] => config_path($config['module']),
            ], 'config');
            $this->mergeConfigFrom(
                $config['path'], basename($config['module'], '.php')
            );
        }
    }

    /**
     * Register additional directories of factories.
     *
     * @return void
     */
    public function loadFactories()
    {
        foreach ($this->bootstrapService->getFactories() as $factory) {
            if (!$this->app->environment('production')) {
                app(Factory::class)->load($factory['path']);
            }
        }
    }

    /**
     * Register additional directories of migrations.
     *
     * @return void
     */
    public function loadMigrations()
    {
        foreach ($this->bootstrapService->getMigrations() as $migration) {
            $this->loadMigrationsFrom($migration['path']);
        }
    }

    private function loadPolicies()
    {
        //TODO
    }

    private function overrideSeedCommand()
    {
        $app = $this->app;
        $service = $this->bootstrapService;
        $this->app->extend('command.seed', function () use ($app, $service) {
            return new SeedCommand($app['db'], $service);
        });
    }
}
