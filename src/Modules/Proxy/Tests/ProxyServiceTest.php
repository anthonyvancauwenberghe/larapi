<?php

namespace Modules\Proxy\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Modules\Proxy\Entities\Proxy;
use Modules\User\Entities\User;

class ProxyServiceTest extends TestCase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Proxy[]
     */
    protected $proxies;

    protected function seedData()
    {
        parent::seedData();
        $this->user = $this->actAsRandomUser();
        $this->proxies = Proxy::fromFactory(5)->create(['user_id' => $this->user->id]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserProxies()
    {
        $Proxys = $this->proxies->toArray();

        $this->assertNotEmpty($Proxys);
    }

    public function testProxyBelongsToUser()
    {
        $Proxy = Proxy::first();
        $user = $Proxy->user;
        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $Proxy->user);
    }
}
