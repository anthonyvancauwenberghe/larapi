<?php

namespace Modules\Machine\Tests;

use Foundation\Abstracts\Tests\HttpTest;
use Modules\Authorization\Entities\Role;

class MachineHttpTest extends HttpTest
{
    protected $roles = Role::USER;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindMachine()
    {
        $user = $this->getHttpUser();
        $machine = $user->machines->first();

        $http = $this->http('GET', '/v1/machines/' . $machine->id);
        $http->assertStatus(200);

        $user->syncRoles(Role::GUEST);
        $http = $this->http('GET', '/v1/machines/' . $machine->id);
        $http->assertStatus(200);
    }

    /**
     * Update a machine test
     *
     * @return void
     */
    public function testUpdateMachine()
    {
        $user = $this->getHttpUser();
        $machine = $user->machines->first();

        /* Test response for a normal user */
        $http = $this->http('PATCH', '/v1/machines/' . $machine->id, []);
        $http->assertStatus(200);

        /* Test response for a guest user */
        $user->syncRoles(Role::GUEST);
        $http = $this->http('PATCH', '/v1/machines/' . $machine->id, []);
        $http->assertStatus(403);
    }
}
