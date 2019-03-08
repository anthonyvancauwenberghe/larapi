<?php

namespace Modules\Schedule\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Schedule\Contracts\ScheduleServiceContract;
use Modules\Schedule\Services\ScheduleService;

class ScheduleServiceProvider extends ServiceProvider
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
            ScheduleServiceContract::class,
            ScheduleService::class
        );
    }
}
