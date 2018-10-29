<?php

namespace Foundation\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        //Telescope::night();

        Telescope::filter(function (IncomingEntry $entry) {
            if ($entry->type === EntryType::REQUEST
                && isset($entry->content['uri'])
                && str_contains($entry->content['uri'], 'horizon')) {
                return false;
            }

            if ($this->app->environment('local')) {
                return true;
            }

            return $entry->isReportableException() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
