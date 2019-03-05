<?php

namespace Modules\Proxy\Tests;

use Modules\Proxy\Contracts\ProxyServiceContract;
use Modules\Proxy\Entities\Proxy;
use Modules\Proxy\Services\ProxyService;
use Modules\Proxy\Transformers\ProxyTransformer;
use Modules\Auth0\Abstracts\Auth0HttpTest;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Entities\Machine;
use Modules\User\Entities\User;


class ProxyHttpTestCase extends Auth0HttpTest
{
    protected $roles = Role::USER;

    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @var Machine
     */
    protected $machine;

    /**
     * @var ProxyService
     */
    protected $service;

    protected function seedData()
    {
        parent::seedData();
        $this->machine = Machine::fromFactory()->create(['user_id' => $this->getUser()->id]);
        $this->proxy = factory(Proxy::class)->create(['machine_id' => $this->machine->id, 'user_id' => $this->getUser()->id]);

        $this->service = $this->app->make(ProxyServiceContract::class);
    }

    public function testAllProxys()
    {
        $this->getUser()->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/proxies');
        $response->assertStatus(200);
        $this->assertEquals(
            ProxyTransformer::collection($this->service->getByUserId($this->getUser()->id))->serialize(),
            $this->decodeHttpResponse($response)
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindProxy()
    {
        $this->getUser()->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/proxys/' . $this->proxy->id);
        $response->assertStatus(200);

        $this->getUser()->syncRoles(Role::GUEST);
        $response = $this->http('GET', '/v1/proxys/' . $this->proxy->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindProxyWithRelations()
    {
        $this->getUser()->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/proxys/' . $this->proxy->id, ['include' => 'machine,user', 'limit' => 3]);
        $response->assertStatus(200);

        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response));
        $this->assertArrayHasKey('machine', $this->decodeHttpResponse($response));

        $response = $this->http('GET', '/v1/proxys/' . $this->proxy->id);
        $response->assertStatus(200);
        $this->assertArrayNotHasKey('user', $this->decodeHttpResponse($response));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindProxyWithoutMachine()
    {
        $this->getUser()->syncRoles(Role::USER);
        $proxy = factory(Proxy::class)->create(['machine_id' => null, 'user_id' => $this->getUser()->id]);
        $response = $this->http('GET', '/v1/proxys/' . $proxy->id, ['include' => 'machine,user', 'limit' => 3]);
        $response->assertStatus(200);

        $this->assertArrayHasKey('machine', $this->decodeHttpResponse($response));
        $this->assertEquals(null, $this->decodeHttpResponse($response)['machine']);
    }

    public function testUnauthorizedAccessProxy()
    {
        $user = factory(User::class)->create();
        $proxy = factory(Proxy::class)->create(['user_id' => $user->id]);

        $this->getUser()->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/proxys/' . $proxy->id);
        $response->assertStatus(403);
    }

    public function testUnauthorizedDeleteProxy()
    {
        $user = factory(User::class)->create();
        $Proxy = factory(Proxy::class)->create(['user_id' => $user->id]);
        $this->getUser()->syncRoles(Role::USER);
        $response = $this->http('DELETE', '/v1/proxys/' . $Proxy->id);
        $response->assertStatus(403);
    }

    public function testDeleteProxy()
    {
        $this->getUser()->syncRoles(Role::USER);
        $response = $this->http('DELETE', '/v1/proxys/' . $this->proxy->id);
        $response->assertStatus(204);
    }

    public function testAdminAccessProxy()
    {
        $user = factory(User::class)->create();
        $Proxy = factory(Proxy::class)->create(['user_id' => $user->id]);

        $this->getUser()->syncRoles(Role::ADMIN);
        $response = $this->http('GET', '/v1/proxys/' . $Proxy->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateProxy()
    {
        $this->getUser()->syncRoles(Role::USER);
        $proxy = Proxy::fromFactory()->make([
            'user_id' => $this->getUser()->id,
        ]);
        $response = $this->http('POST', '/v1/proxys', $proxy->toArray());
        $response->assertStatus(201);
        $this->assertArrayHasKey('username', $this->decodeHttpResponse($response));
        $this->assertArrayHasKey('password', $this->decodeHttpResponse($response));
    }

    /**
     * Update a Proxy test.
     *
     * @return void
     */
    public function testUpdateProxy()
    {
        $this->getUser()->syncRoles(Role::USER);
        /* Test response for a normal user */
        $response = $this->http('PATCH', '/v1/proxys/' . $this->proxy->id, []);
        $response->assertStatus(200);

        /* Test response for a guest user */
        $this->getUser()->syncRoles(Role::GUEST);
        $this->assertFalse($this->getUser()->hasRole(Role::USER));
        $this->assertTrue($this->getUser()->hasRole(Role::GUEST));

        $response = $this->http('PATCH', '/v1/proxys/' . $this->proxy->id, []);
        $response->assertStatus(403);
    }
}
