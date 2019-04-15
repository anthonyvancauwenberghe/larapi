<?php

namespace Modules\Proxy\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Proxy\Contracts\ProxyRepositoryContract;
use Modules\Proxy\Contracts\ProxyServiceContract;
use Modules\Proxy\Repositories\ProxyRepository;
use Modules\Proxy\Services\ProxyService;

class ProxyServiceProvider extends ServiceProvider
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
            ProxyServiceContract::class,
            ProxyService::class
        );

        $this->app->bind(
            ProxyRepositoryContract::class,
            ProxyRepository::class
        );
    }
}
