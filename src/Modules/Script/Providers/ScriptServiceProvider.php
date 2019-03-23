<?php

namespace Modules\Script\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Script\Contracts\ScriptServiceContract;
use Modules\Script\Services\ScriptService;

class ScriptServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
       //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
                    ScriptServiceContract::class,
                    ScriptService::class
                );
    }
}
