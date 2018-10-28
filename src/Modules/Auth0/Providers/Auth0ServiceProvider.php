<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 13.10.18
 * Time: 20:49.
 */

namespace Modules\Auth0\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Auth0\Contracts\Auth0ServiceContract;
use Modules\Auth0\Services\Auth0Service;
use Modules\User\Services\UserService;

class Auth0ServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //  'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\Auth0\Login\Contract\Auth0UserRepository::class, function () {
            return new Auth0Service(new UserService());
        });

        $this->app->bind(Auth0ServiceContract::class, function () {
            return new Auth0Service(new UserService());
        });
    }
}
