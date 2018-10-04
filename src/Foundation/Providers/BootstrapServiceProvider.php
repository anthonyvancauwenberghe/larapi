<?php

namespace Foundation\Providers;

use Foundation\Services\BootstrapRegistrarService;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    protected $bootstrapService;

    /**
     * BootstrapServiceProvider constructor.
     */
    public function __construct()
    {
        parent::__construct(app());
        $this->bootstrapService = new BootstrapRegistrarService();

        if (env('APP_ENV') !== 'production') {
            $this->bootstrapService->cache();
        }
    }


    public function register()
    {
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
