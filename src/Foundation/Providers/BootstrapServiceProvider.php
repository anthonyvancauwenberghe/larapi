<?php

namespace Foundation\Providers;

use Foundation\Console\SeedCommand;
use Foundation\Contracts\ConditionalAutoRegistration;
use Foundation\Contracts\ModelPolicyContract;
use Foundation\Contracts\Ownable;
use Foundation\Observers\CacheObserver;
use Foundation\Policies\OwnershipPolicy;
use Foundation\Services\BootstrapRegistrarService;
use Foundation\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Event;
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
        /* Load cache observers only when caching is enabled */
        if (config('model.caching')) {
            $this->loadCacheObservers();
        }
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
        $this->loadListeners();

        /* Override the seed command with the larapi custom one */
        $this->overrideSeedCommand();


        $this->loadOwnershipPolicies();

        /* Register Policies after ownership policies otherwise they would not get overriden */
        $this->loadPolicies();

        /* Register all Module Service providers.
        ** Always load at the end so the user has the ability to override certain functionality
         * */
        $this->loadServiceProviders();
    }

    private function loadBootstrapService()
    {
        $this->bootstrapService = new BootstrapRegistrarService();

        if (!($this->app->environment('production') || $this->app->environment('testing'))) {
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
            $fileNameArray = explode('.', $route['filename']);
            $routePrefix = $fileNameArray[0];
            $version = $fileNameArray[1];

            if ($version === 'php')
                $prefix = $routePrefix;
            else
                $prefix = $version . '/' . $routePrefix;

            Route::group([
                'prefix' => $prefix,
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
            if (isset($config['filename']) && is_string($config['filename'])) {
                $fileName = $config['filename'];
                $configName = strtolower(explode('.', $fileName)[0]);
                $this->mergeConfigFrom(
                    $config['path'], $configName
                );
            }
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
            if (class_implements_interface($policy['class'], ModelPolicyContract::class)) {
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
            if (class_uses_trait($model, Cacheable::class)) {
                $model::observe(CacheObserver::class);
            }
        }
    }

    private function loadOwnershipPolicies()
    {
        foreach ($this->bootstrapService->getModels() as $model) {
            if (class_implements_interface($model, Ownable::class)) {
                Gate::policy($model, OwnershipPolicy::class);
                Gate::define('access', OwnershipPolicy::class . '@access');
            }
        }
    }

    private function loadServiceProviders()
    {
        foreach ($this->bootstrapService->getProviders() as $provider) {
            if ($this->passedRegistrationCondition($provider))
                $this->app->register($provider);
        }
    }

    private function loadListeners()
    {
        foreach ($this->bootstrapService->getEvents() as $event) {
            foreach ($event['listeners'] as $listener) {
                Event::listen($event['class'], $listener);
            }
        }
    }

    private function passedRegistrationCondition($class)
    {
        if (!class_implements_interface($class, ConditionalAutoRegistration::class))
            return true;
        return run_class_function($class, 'registrationCondition');
    }
}
