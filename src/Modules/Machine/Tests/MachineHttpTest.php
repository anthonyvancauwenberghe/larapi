<?php

namespace Modules\Machine\Tests;

use Modules\Auth0\Abstracts\AuthorizedHttpTest;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Contracts\MachineServiceContract;
use Modules\Machine\Entities\Machine;
use Modules\Machine\Services\MachineService;
use Modules\Machine\Transformers\MachineTransformer;
use Modules\User\Entities\User;

class MachineHttpTest extends AuthorizedHttpTest
{
    protected $roles = Role::MEMBER;

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
        $this->machine = factory(Machine::class)->create(['user_id' => $this->getUser()->id]);
        $this->service = $this->app->make(MachineServiceContract::class);
    }

    public function testAllMachines()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        $response = $this->http('GET', '/v1/machines');
        $this->assertEquals(
            MachineTransformer::collection($this->service->getByUserId($this->getUser()->id))->serialize(),
            $this->decodeHttpResponse($response)
        );
        $response->assertStatus(200);
    }

    public function testAllMachinesWithUserRelation()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        $response = $this->http('GET', '/v1/machines?include=user');
        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response)[0]);
    }

    public function testAllMachinesWithAccountsRelation()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        $response = $this->http('GET', '/v1/machines?include=accounts');
        $response->assertStatus(200);
        $this->assertArrayHasKey('accounts', $this->decodeHttpResponse($response)[0]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindMachine()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        $response = $this->http('GET', '/v1/machines/'.$this->machine->id);
        $response->assertStatus(200);

        $this->getUser()->syncRoles(Role::GUEST);
        $response = $this->http('GET', '/v1/machines/'.$this->machine->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindMachineWithRelations()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        $response = $this->http('GET', '/v1/machines/'.$this->machine->id, ['include' => 'user']);
        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response));

        $response = $this->http('GET', '/v1/machines/'.$this->machine->id);
        $response->assertStatus(200);
        $this->assertArrayNotHasKey('user', $this->decodeHttpResponse($response));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindMachineWithFaultyRelations()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        $response = $this->http('GET', '/v1/machines/'.$this->machine->id, ['include' => 'userx,blabla,user']);
        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response));
        $this->assertArrayNotHasKey('userx', $this->decodeHttpResponse($response));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAllMachinesWithRelations()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        Machine::fromFactory(5)->create(['user_id' => $this->getUser()->id]);
        $response = $this->http('GET', '/v1/machines/', ['include' => 'user']);
        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response)[0]);

        $response = $this->http('GET', '/v1/machines/');
        $response->assertStatus(200);
        $this->assertArrayNotHasKey('user', $this->decodeHttpResponse($response)[0]);
    }

    public function testAllMachinesWithLimit()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        Machine::fromFactory(5)->create(['user_id' => $this->getUser()->id]);
        $machineCount = $this->service->getByUserId($this->getUser()->id)->count();
        $response = $this->http('GET', '/v1/machines/');
        $response->assertStatus(200);
        $this->assertCount($machineCount, $this->decodeHttpResponse($response));

        $limit = 3;
        $response = $this->http('GET', '/v1/machines/', ['limit' => $limit]);
        $response->assertStatus(200);
        $this->assertCount($limit, $this->decodeHttpResponse($response));
    }

    public function testUnauthorizedAccessMachine()
    {
        $user = factory(User::class)->create();
        $machine = factory(Machine::class)->create(['user_id' => $user->id]);
        $this->getUser()->syncRoles(Role::MEMBER);
        $response = $this->http('GET', '/v1/machines/'.$machine->id);
        $response->assertStatus(403);
    }

    public function testUnauthorizedDeleteMachine()
    {
        $user = factory(User::class)->create();
        $machine = factory(Machine::class)->create(['user_id' => $user->id]);
        $this->getUser()->syncRoles(Role::MEMBER);
        $response = $this->http('DELETE', '/v1/machines/'.$machine->id);
        $response->assertStatus(403);
    }

    public function testDeleteMachine()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        $response = $this->http('DELETE', '/v1/machines/'.$this->machine->id);
        $response->assertStatus(204);
    }

    public function testAdminAccessMachine()
    {
        $user = factory(User::class)->create();
        $machine = factory(Machine::class)->create(['user_id' => $user->id]);

        $this->getUser()->syncRoles(Role::ADMIN);
        $response = $this->http('GET', '/v1/machines/'.$machine->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateMachine()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        $machine = factory(Machine::class)->raw([
            'user_id' => $this->getUser()->id,
        ]);
        $response = $this->http('POST', '/v1/machines', $machine);
        $response->assertStatus(201);
       //TODO REPLACE DEPRECATED METHOD $this->assertArraySubset($machine, $this->decodeHttpResponse($response));
    }

    /**
     * Update a machine test.
     *
     * @return void
     */
    public function testUpdateMachine()
    {
        $this->getUser()->syncRoles(Role::MEMBER);
        /* Test response for a normal user */
        $response = $this->http('PATCH', '/v1/machines/'.$this->machine->id, []);
        $response->assertStatus(200);

        /* Test response for a guest user */
        $this->getUser()->syncRoles(Role::GUEST);
        $this->assertFalse($this->getUser()->hasRole(Role::MEMBER));
        $this->assertTrue($this->getUser()->hasRole(Role::GUEST));

        $response = $this->http('PATCH', '/v1/machines/'.$this->machine->id, []);
        $response->assertStatus(403);
    }
}
