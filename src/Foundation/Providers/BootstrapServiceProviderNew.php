<?php

namespace Foundation\Providers;

use Foundation\Console\SeedCommand;
use Foundation\Contracts\ConditionalAutoRegistration;
use Foundation\Contracts\ModelPolicyContract;
use Foundation\Contracts\Ownable;
use Foundation\Core\Larapi;
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
class BootstrapServiceProviderNew extends ServiceProvider
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

        if (!($this->app->environment('production'))) {
            $this->bootstrapService->recache();
        }
    }

    private function loadCommands()
    {
        foreach ($this->bootstrapService->getCommands() as $commandResource) {
            $this->commands($commandResource->getClasses());
        }
    }


    private function loadRoutes()
    {
        foreach ($this->bootstrapService->getRoutes() as $routeResource) {

            foreach ($routeResource->getFiles() as $file) {
                $prefixArray = explode('.', $file->getFileName());
                $path = $file->getPath();
                Route::group([
                    'prefix' => $prefixArray[1] . '/' . $prefixArray[0],
                    'namespace' => $routeResource->getModule()->getControllers()->getNamespace(),
                    'domain' => Larapi::getApiDomainName(),
                    'middleware' => ['api'],
                ], function () use ($path) {
                    require $path;
                });
            }

            Route::model(strtolower($routeResource->getModule()->getName()), $routeResource->getModule()->getMainModel());
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function loadConfigs()
    {
        foreach ($this->bootstrapService->getConfigs() as $configResource) {
            foreach($configResource->getFiles() as $file){
                $this->mergeConfigFrom(
                    $file->getPath(), $file->getName()
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
        foreach ($this->bootstrapService->getFactories() as $factoryResource) {
            if (!$this->app->environment('production')) {
                app(Factory::class)->load($factoryResource->getPath());
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
        foreach ($this->bootstrapService->getMigrations() as $migrationResource) {
            $this->loadMigrationsFrom($migrationResource->getPath());
        }
    }

    private function loadPolicies()
    {
        foreach ($this->bootstrapService->getPolicies() as $policyResource) {
            //TODO
                /*if (class_implements_interface($policy, ModelPolicyContract::class)) {
                    Gate::policy($policyResource->getModule()->getMainModel(), $policy['class']);
                }*/
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
        foreach ($this->bootstrapService->getModels()->getClasses() as $model) {
            if (class_implements_interface($model, Ownable::class)) {
                Gate::policy($model, OwnershipPolicy::class);
                Gate::define('access', OwnershipPolicy::class . '@access');
            }
        }
    }

    private function loadServiceProviders()
    {
        foreach ($this->bootstrapService->getProviders()->getClasses() as $provider) {
            if ($this->passedRegistrationCondition($provider)) {
                $this->app->register($provider);
            }
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
        if (!class_implements_interface($class, ConditionalAutoRegistration::class)) {
            return true;
        }

        return call_class_function($class, 'registrationCondition');
    }
}
