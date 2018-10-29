<?php

namespace Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_ENV') === 'local') {
            $this->registerLocalPackages();
        }
        if (env('APP_ENV') !== 'testing') {
            $this->registerNonTestingPackages();
        }
    }

    private function registerLocalPackages()
    {
        $this->app->register(\Nwidart\Modules\LaravelModulesServiceProvider::class);
        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        $this->app->register(\Foundation\Providers\TelescopeServiceProvider::class);
    }

    private function registerNonTestingPackages()
    {
        $this->app->register(\Laravel\Horizon\HorizonServiceProvider::class);
    }
}
