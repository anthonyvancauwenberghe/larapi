<?php

namespace Modules\User\Tests;

use Foundation\Abstracts\Tests\HttpTest;
use Modules\Authorization\Entities\Role;
use Modules\User\Entities\User;

class UserHttpTest extends HttpTest
{
    protected $roles = Role::USER;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetUser()
    {
        $http = $this->http('GET', '/v1/users/me');
        $http->assertStatus(200);
        $this->assertArraySubset($this->decodeHttpContent($http, false), get_authenticated_user()->toArray());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAssignRole()
    {
        $user = User::all()->last();
        $this->assertFalse($user->hasRole(Role::ADMIN));

        $http = $this->http('PATCH', '/v1/users/'.$user->id, ['roles' => Role::ADMIN]);
        $http->assertStatus(403);

        $this->changeTestUserRoles(Role::ADMIN);

        $http = $this->http('PATCH', '/v1/users/'.$user->id, ['roles' => Role::ADMIN]);
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
