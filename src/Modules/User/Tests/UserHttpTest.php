<?php

namespace Modules\User\Tests;

use Foundation\Abstracts\Tests\HttpTest;
use Modules\Authorization\Entities\Role;
use Modules\User\Entities\User;
use Modules\User\Resources\UserResource;

class UserHttpTest extends HttpTest
{
    protected $roles = Role::USER;

    protected $users;

    protected function seedData()
    {
        parent::seedData();
        $this->users = factory(User::class, 5)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetUser()
    {
        $user = $this->getHttpUser();
        $http = $this->http('GET', '/v1/users/me');
        $http->assertStatus(200);
        //TODO $this->assertArraySubset((new UserResource($user))->toArray(null),$this->decodeHttpContent($http));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAssignRole()
    {
        $user = $this->getHttpUser();

        $this->assertFalse($user->hasRole(Role::ADMIN));

        $http = $this->http('PATCH', '/v1/users/'.$user->id, ['roles' => [Role::ADMIN]]);
        $http->assertStatus(403);

        $this->changeTestUserRoles(Role::ADMIN);
        $this->assertTrue($user->fresh()->hasRole(Role::ADMIN));
        $http = $this->http('PATCH', '/v1/users/' . $user->id, ['roles' => [Role::ADMIN]]);
        $http->assertStatus(200);

        $user = User::find($user->id);
        $this->assertTrue($user->hasRole(Role::ADMIN));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexUsers()
    {
        $http = $this->http('GET', '/v1/users');
        $http->assertStatus(403);

        $this->changeTestUserRoles(Role::ADMIN);

        $http = $this->http('GET', '/v1/users');
        $http->assertStatus(200);
    }
}
