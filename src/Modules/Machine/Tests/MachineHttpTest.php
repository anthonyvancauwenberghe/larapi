<?php

namespace Modules\Machine\Tests;

use Foundation\Abstracts\Tests\HttpTest;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Contracts\MachineServiceContract;
use Modules\Machine\Entities\Machine;
use Modules\Machine\Resources\MachineResource;
use Modules\Machine\Services\MachineService;
use Modules\User\Entities\User;

class MachineHttpTest extends HttpTest
{
    protected $roles = Role::USER;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Machine
     */
    protected $machine;

    /**
     * @var MachineService
     */
    protected $service;


    protected function seedData()
    {
        parent::seedData();
        $this->user = $this->getHttpUser();
        $this->machine = factory(Machine::class)->create(['user_id' => $this->user->id]);
        $this->service = $this->app->make(MachineServiceContract::class);
    }

    public function testAllMachines()
    {
        $this->user->syncRoles(Role::USER);
        $http = $this->http('GET', '/v1/machines');
        $this->assertEquals(
            MachineResource::collection($this->service->allByUserId($this->user->id))->toArray(null),
            $this->decodeHttpContent($http)
        );
        $http->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindMachine()
    {
        $this->user->syncRoles(Role::USER);
        $http = $this->http('GET', '/v1/machines/' . $this->machine->id);
        $http->assertStatus(200);

        $this->user->syncRoles(Role::GUEST);
        $http = $this->http('GET', '/v1/machines/' . $this->machine->id);
        $http->assertStatus(200);
    }

    /**
     * Update a machine test.
     *
     * @return void
     */
    public function testUpdateMachine()
    {
        $this->user->syncRoles(Role::USER);
        /* Test response for a normal user */
        $http = $this->http('PATCH', '/v1/machines/' . $this->machine->id, []);
        $http->assertStatus(200);

        /* Test response for a guest user */
        $this->user->syncRoles(Role::GUEST);
        $this->assertFalse($this->user->hasRole(Role::USER));
        $this->assertTrue($this->user->hasRole(Role::GUEST));

        $http = $this->http('PATCH', '/v1/machines/' . $this->machine->id, []);
        $http->assertStatus(403);
    }
}
