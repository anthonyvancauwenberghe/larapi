<?php

namespace Modules\Machine\Tests;

use Foundation\Abstracts\Tests\HttpTest;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Contracts\MachineServiceContract;
use Modules\Machine\Entities\Machine;
use Modules\Machine\Services\MachineService;
use Modules\Machine\Transformers\MachineTransformer;
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
        $response = $this->http('GET', '/v1/machines');
        $this->assertEquals(
            MachineTransformer::collection($this->service->getByUserId($this->user->id))->serialize(),
            $this->decodeHttpResponse($response)
        );
        $response->assertStatus(200);
    }

    public function testAllMachinesWithUserRelation()
    {
        $this->user->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/machines?include=user');
        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response)[0]);
    }

    public function testAllMachinesWithAccountsRelation()
    {
        $this->user->syncRoles(Role::USER);
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
        $this->user->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/machines/'.$this->machine->id);
        $response->assertStatus(200);

        $this->user->syncRoles(Role::GUEST);
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
        $this->user->syncRoles(Role::USER);
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
        $this->user->syncRoles(Role::USER);
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
        $this->user->syncRoles(Role::USER);
        Machine::fromFactory(5)->create(['user_id' => $this->user->id]);
        $response = $this->http('GET', '/v1/machines/', ['include' => 'user']);
        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response)[0]);

        $response = $this->http('GET', '/v1/machines/');
        $response->assertStatus(200);
        $this->assertArrayNotHasKey('user', $this->decodeHttpResponse($response)[0]);
    }

    public function testUnauthorizedAccessMachine()
    {
        $user = factory(User::class)->create();
        $machine = factory(Machine::class)->create(['user_id' => $user->id]);
        $this->user->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/machines/'.$machine->id);
        $response->assertStatus(403);
    }

    public function testUnauthorizedDeleteMachine()
    {
        $user = factory(User::class)->create();
        $machine = factory(Machine::class)->create(['user_id' => $user->id]);
        $this->user->syncRoles(Role::USER);
        $response = $this->http('DELETE', '/v1/machines/'.$machine->id);
        $response->assertStatus(403);
    }

    public function testDeleteMachine()
    {
        $this->user->syncRoles(Role::USER);
        $response = $this->http('DELETE', '/v1/machines/'.$this->machine->id);
        $response->assertStatus(204);
    }

    public function testAdminAccessMachine()
    {
        $user = factory(User::class)->create();
        $machine = factory(Machine::class)->create(['user_id' => $user->id]);

        $this->user->syncRoles(Role::ADMIN);
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
        $this->user->syncRoles(Role::USER);
        $machine = factory(Machine::class)->raw([
            'user_id' => $this->user->id,
        ]);
        $response = $this->http('POST', '/v1/machines', $machine);
        $response->assertStatus(201);
        $this->assertArraySubset($machine, $this->decodeHttpResponse($response));
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
        $response = $this->http('PATCH', '/v1/machines/'.$this->machine->id, []);
        $response->assertStatus(200);

        /* Test response for a guest user */
        $this->user->syncRoles(Role::GUEST);
        $this->assertFalse($this->user->hasRole(Role::USER));
        $this->assertTrue($this->user->hasRole(Role::GUEST));

        $response = $this->http('PATCH', '/v1/machines/'.$this->machine->id, []);
        $response->assertStatus(403);
    }
}
