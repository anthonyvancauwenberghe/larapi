<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.10.18
 * Time: 21:56.
 */

namespace Foundation\Abstracts\Tests;

use Foundation\Traits\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Authorization\Contracts\AuthorizationContract;
use Modules\User\Contracts\UserServiceContract;
use Modules\User\Entities\User;
use Modules\User\Services\UserService;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, CreatesApplication;

    /**
     * @var UserService
     */
    private $userService;

    public function setUp()
    {
        parent::setUp();
        $this->userService = $this->app->make(UserServiceContract::class);
        $this->app->make(AuthorizationContract::class)->clearPermissionCache();
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        $this->seedData();
    }

    protected function seedData()
    {
    }

    protected function createUser()
    {
        return $this->userService->create(factory(User::class)->raw());
    }

    protected function actAsRandomUser()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $user = $this->createUser();
        } else {
            $user = $users->random();
        }

        $this->actingAs($user);

        return $user;
    }
}
