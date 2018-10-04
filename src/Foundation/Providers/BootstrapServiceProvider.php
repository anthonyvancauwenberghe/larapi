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

        if (env('APP_ENV') !== 'production') {
            $this->bootstrapService->cache();
        }
    }

    private function loadCommands()
    {
        $this->commands($this->bootstrapService->getCommands());
    }

    private function loadRoutes()
    {
        foreach ($this->bootstrapService->getRoutes() as $route) {
            $apiDomain = strtolower(env('API_URL'));
            $apiDomain = str_replace('http://', '', $apiDomain);
            $apiDomain = str_replace('https://', '', $apiDomain);
            $namespace = $route[1] . '\\' . 'Http\\Controllers';
            $filepath = $route[0];
            Route::group([
                'prefix' => 'v1',
                'namespace' => $namespace,
                'domain' => $apiDomain,
                'middleware' => []
            ], function (Router $router) use ($filepath) {
                require $filepath;
            });
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function loadConfigs()
    {
        foreach ($this->bootstrapService->getConfigs() as $route) {
            $this->publishes([
                $route[0] => config_path($route[1]),
            ], 'config');
            $this->mergeConfigFrom(
                $route[0], basename($route[1], '.php')
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
        foreach ($this->bootstrapService->getFactories() as $factoryPath) {
            if (!app()->environment('production')) {
                app(Factory::class)->load($factoryPath);
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
        foreach ($this->bootstrapService->getMigrations() as $migrationPath) {
            $this->loadMigrationsFrom($migrationPath);
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
