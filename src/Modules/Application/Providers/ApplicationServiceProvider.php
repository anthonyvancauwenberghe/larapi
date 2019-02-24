<?php

namespace Modules\Application\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Application\Contracts\ApplicationServiceContract;
use Modules\Application\Services\ApplicationService;

class ApplicationServiceProvider extends ServiceProvider
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
            ApplicationServiceContract::class,
            ApplicationService::class
        );
    }
}
