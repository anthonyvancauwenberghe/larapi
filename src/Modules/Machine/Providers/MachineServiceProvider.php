<?php

namespace Modules\Machine\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Machine\Contracts\NotificationServiceContract;
use Modules\Machine\Services\NotificationService;

class MachineServiceProvider extends ServiceProvider
{
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
        $this->app->bind(
            NotificationServiceContract::class,
            NotificationService::class
        );
    }
}
