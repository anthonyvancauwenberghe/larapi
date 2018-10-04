<?php

namespace Foundation\Providers;

use Foundation\Services\BootstrapRegistrarService;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class BootstrapServiceProvider.
 */
class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * @var BootstrapRegistrarService
     */
    protected $bootstrapService;

    public function bootRegistrarService()
    {
        $this->bootstrapService = new BootstrapRegistrarService();

        if (env('APP_ENV') !== 'production') {
            $this->bootstrapService->cache();
        }
    }

    public function register()
    {
        $this->bootRegistrarService();
        $this->loadModuleCommands();
        $this->loadModulePolicies();
    }

    private function loadModuleCommands()
    {
        $this->commands($this->bootstrapService->getCommands());
    }

    private function loadModulePolicies()
    {
        //TODO
    }
}
