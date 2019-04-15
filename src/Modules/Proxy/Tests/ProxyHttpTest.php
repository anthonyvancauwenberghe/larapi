<?php

namespace Modules\Proxy\Tests;

use Modules\Auth0\Abstracts\AuthorizedHttpTest;
use Modules\Authorization\Entities\Role;
use Modules\Proxy\Contracts\ProxyServiceContract;
use Modules\Proxy\Entities\Proxy;
use Modules\Proxy\Events\ProxyCreatedEvent;
use Modules\Proxy\Events\ProxyUpdatedEvent;
use Modules\Proxy\Services\ProxyService;
use Modules\Proxy\Transformers\ProxyTransformer;
use Modules\User\Entities\User;

class ProxyHttpTest extends AuthorizedHttpTest
{
    protected $roles = Role::MEMBER;

    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @var ProxyService
     */
    protected $service;

    protected function seedData()
    {
        parent::seedData();
        $this->proxy = factory(Proxy::class)->create(['user_id' => $this->getActingUser()->id]);
        $this->service = $this->app->make(ProxyServiceContract::class);
    }

    public function testAllProxies()
    {
        $response = $this->http('GET', '/v1/proxies');
        $response->assertStatus(200);
        $this->assertEquals(
            ProxyTransformer::collection($this->service->getByUserId($this->getActingUser()->id))->serialize(),
            $response->decode()
        );
    }

    public function testAllProxiesAsAdmin()
    {
        $this->getActingUser()->syncRoles(Role::ADMIN);
        $this->testAllProxies();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindProxy()
    {
        $response = $this->http('GET', '/v1/proxies/' . $this->proxy->id);
        $response->assertStatus(200);

        $this->getActingUser()->syncRoles(Role::GUEST);
        $response = $this->http('GET', '/v1/proxies/' . $this->proxy->id);
        $response->assertStatus(403);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindProxyWithRelations()
    {
        $response = $this->http('GET', '/v1/proxies/' . $this->proxy->id, ['include' => 'user', 'limit' => 3]);
        $response->assertStatus(200);

        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response));

        $response = $this->http('GET', '/v1/proxies/' . $this->proxy->id);
        $response->assertStatus(200);
        $this->assertArrayNotHasKey('user', $this->decodeHttpResponse($response));
    }

    public function testUnauthorizedAccessProxy()
    {
        $user = factory(User::class)->create();
        $proxy = factory(Proxy::class)->create(['user_id' => $user->id]);

        $response = $this->http('GET', '/v1/proxies/' . $proxy->id);
        $response->assertStatus(403);
    }

    public function testUnauthorizedDeleteProxy()
    {
        $user = factory(User::class)->create();
        $Proxy = factory(Proxy::class)->create(['user_id' => $user->id]);
        $response = $this->http('DELETE', '/v1/proxies/' . $Proxy->id);
        $response->assertStatus(403);
    }

    public function testDeleteProxy()
    {
        $this->expectsEvents(ProxyDel::class);
        $response = $this->http('DELETE', '/v1/proxies/' . $this->proxy->id);
        $response->assertStatus(204);
    }

    public function testAdminAccessProxy()
    {
        $user = factory(User::class)->create();
        $Proxy = factory(Proxy::class)->create(['user_id' => $user->id]);

        $this->getActingUser()->syncRoles(Role::ADMIN);
        $response = $this->http('GET', '/v1/proxies/' . $Proxy->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateProxy()
    {
        $proxy = Proxy::fromFactory()->make([
            'user_id' => $this->getActingUser()->id,
        ]);
        $this->expectsEvents(ProxyCreatedEvent::class);
        $response = $this->http('POST', '/v1/proxies', $proxy->toArray());
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
        /* Test response for a normal user */
        $this->expectsEvents(ProxyUpdatedEvent::class);
        $response = $this->http('PATCH', '/v1/proxies/' . $this->proxy->id, []);
        $response->assertStatus(200);

        /* Test response for a guest user */
        $this->getActingUser()->syncRoles(Role::GUEST);
        $this->assertFalse($this->getActingUser()->hasRole(Role::MEMBER));
        $this->assertTrue($this->getActingUser()->hasRole(Role::GUEST));

        $response = $this->http('PATCH', '/v1/proxies/' . $this->proxy->id, []);
        $response->assertStatus(403);
    }
}
