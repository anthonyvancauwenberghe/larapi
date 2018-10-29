<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

use Modules\Auth0\Services\Auth0Service;
use Modules\Authorization\Contracts\AuthorizationContract;
use Modules\Authorization\Services\AuthorizationService;
use Modules\User\Services\UserService;

$app = new Illuminate\Foundation\Application(
    dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    \Foundation\Kernels\HttpKernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    \Foundation\Kernels\ConsoleKernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    \Foundation\Kernels\ExceptionKernel::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
