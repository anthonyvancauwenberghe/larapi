<?php

namespace Foundation\Providers;


use Foundation\Console\SeedCommand;
use Foundation\Contracts\Cacheable;
use Foundation\Contracts\ModelPolicyContract;
use Foundation\Contracts\Ownable;
use Foundation\Observers\CacheObserver;
use Foundation\Policies\OwnershipPolicy;
use Foundation\Services\BootstrapRegistrarService;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
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


    public function boot()
    {
        $this->overrideSeedCommand();
        $this->loadCacheObservers();
        $this->loadOwnershipPolicies();

        /* Register Policies after ownership policies otherwise they would not get overriden */
        $this->loadPolicies();
    }


    public function register()
    {
        /* Load BootstrapService here because of the dependencies needed in BootstrapRegistrarService */
        $this->loadBootstrapService();

        $this->loadCommands();
        $this->loadRoutes();
        $this->loadConfigs();
        $this->loadFactories();
        $this->loadMigrations();
    }

    private function loadBootstrapService()
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
            ], function () use ($path) {
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
        foreach ($this->bootstrapService->getPolicies() as $policy) {
            if (classImplementsInterface($policy['class'], ModelPolicyContract::class)) {
                Gate::policy($policy['model'], $policy['class']);
            }
        }
    }

    private function overrideSeedCommand()
    {
        $app = $this->app;
        $service = $this->bootstrapService;
        $this->app->extend('command.seed', function () use ($app, $service) {
            return new SeedCommand($app['db'], $service);
        });
    }

    private function loadCacheObservers()
    {
        foreach ($this->bootstrapService->getModels() as $model) {
            if (classImplementsInterface($model, Cacheable::class) && (bool)config('model.caching')) {
                $model::observe(CacheObserver::class);
            }
        }
    }

    private function loadOwnershipPolicies()
    {
        foreach ($this->bootstrapService->getModels() as $model) {
            if (classImplementsInterface($model, Ownable::class)) {
                Gate::policy($model, OwnershipPolicy::class);
                Gate::define('access', OwnershipPolicy::class . '@access');
            }
        }
    }
}
