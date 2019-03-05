<?php

namespace Modules\User\Tests;

use Modules\Auth0\Abstracts\Auth0HttpTest;
use Modules\Authorization\Entities\Role;
use Modules\User\Entities\User;
use Modules\User\Transformers\UserTransformer;

class UserHttpTest extends Auth0HttpTest
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
        $user = $this->getUser();
        $http = $this->http('GET', '/v1/users/me');
        $http->assertStatus(200);
        $userTransformer = UserTransformer::resource($user)->serialize();
        $httpData = $this->decodeHttpResponse($http);
        $this->assertEquals($userTransformer, $httpData);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAssignRole()
    {
        $user = $this->getUser();

        $this->assertFalse($user->hasRole(Role::ADMIN));

        $http = $this->http('PATCH', '/v1/users/'.$user->id, ['roles' => [Role::ADMIN]]);
        $http->assertStatus(403);

        $this->setUserRoles(Role::ADMIN);
        $this->assertTrue($user->fresh()->hasRole(Role::ADMIN));
        $http = $this->http('PATCH', '/v1/users/'.$user->id, ['roles' => [Role::ADMIN]]);
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

        $this->setUserRoles(Role::ADMIN);

        $http = $this->http('GET', '/v1/users');
        $http->assertStatus(200);
    }
}
