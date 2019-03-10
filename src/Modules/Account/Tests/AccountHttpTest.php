<?php

namespace Modules\Account\Tests;

use Modules\Account\Contracts\AccountServiceContract;
use Modules\Account\Entities\Account;
use Modules\Account\Services\AccountService;
use Modules\Account\Transformers\AccountTransformer;
use Modules\Auth0\Abstracts\AuthorizedHttpTest;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Entities\Machine;
use Modules\User\Entities\User;

class AccountHttpTest extends AuthorizedHttpTest
{
    protected $roles = Role::MEMBER;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var Machine
     */
    protected $machine;

    /**
     * @var AccountService
     */
    protected $service;

    protected function seedData()
    {
        parent::seedData();
        $this->machine = Machine::fromFactory()->create(['user_id' => $this->getActingUser()->id]);
        $this->account = factory(Account::class)->create(['machine_id' => $this->machine->id, 'user_id' => $this->getActingUser()->id]);

        $this->service = $this->app->make(AccountServiceContract::class);
    }

    public function testAllAccounts()
    {
        $response = $this->http('GET', '/v1/accounts');
        $response->assertStatus(200);
        $this->assertEquals(
            AccountTransformer::collection($this->service->getByUserId($this->getActingUser()->id))->serialize(),
            $this->decodeHttpResponse($response)
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindAccount()
    {
        $response = $this->http('GET', '/v1/accounts/'.$this->account->id);
        $response->assertStatus(200);

        $this->getActingUser()->syncRoles(Role::GUEST);
        $response = $this->http('GET', '/v1/accounts/'.$this->account->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindAccountWithRelations()
    {
        $response = $this->http('GET', '/v1/accounts/'.$this->account->id, ['include' => 'machine,user', 'limit' => 3]);
        $response->assertStatus(200);

        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response));
        $this->assertArrayHasKey('machine', $this->decodeHttpResponse($response));

        $response = $this->http('GET', '/v1/accounts/'.$this->account->id);
        $response->assertStatus(200);
        $this->assertArrayNotHasKey('user', $this->decodeHttpResponse($response));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindAccountWithoutMachine()
    {
        $account = factory(Account::class)->create(['machine_id' => null, 'user_id' => $this->getActingUser()->id]);
        $response = $this->http('GET', '/v1/accounts/'.$account->id, ['include' => 'machine,user', 'limit' => 3]);
        $response->assertStatus(200);

        $this->assertArrayHasKey('machine', $this->decodeHttpResponse($response));
        $this->assertEquals(null, $this->decodeHttpResponse($response)['machine']);
    }

    public function testUnauthorizedAccessAccount()
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create(['user_id' => $user->id]);

        $response = $this->http('GET', '/v1/accounts/'.$account->id);
        $response->assertStatus(403);
    }

    public function testUnauthorizedDeleteAccount()
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create(['user_id' => $user->id]);

        $response = $this->http('DELETE', '/v1/accounts/'.$account->id);
        $response->assertStatus(403);
    }

    public function testDeleteAccount()
    {
        $this->getActingUser()->syncRoles(Role::MEMBER);
        $response = $this->http('DELETE', '/v1/accounts/'.$this->account->id);
        $response->assertStatus(204);
    }

    public function testAdminAccessAccount()
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create(['user_id' => $user->id]);

        $this->getActingUser()->syncRoles(Role::ADMIN);
        $response = $this->http('GET', '/v1/accounts/'.$account->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateAccount()
    {
        $account = Account::fromFactory()->make([
            'user_id' => $this->getActingUser()->id,
        ]);

        $response = $this->http('POST', '/v1/accounts', $account->toArray());
        $response->assertStatus(201);

        $this->assertArrayHasKey('username', $this->decodeHttpResponse($response));
        $this->assertArrayHasKey('password', $this->decodeHttpResponse($response));
    }

    /**
     * Update a Account test.
     *
     * @return void
     */
    public function testUpdateAccount()
    {
        /* Test response for a normal user */
        $response = $this->http('PATCH', '/v1/accounts/'.$this->account->id, []);
        $response->assertStatus(200);

        /* Test response for a guest user */
        $this->getActingUser()->syncRoles(Role::GUEST);
        $this->assertFalse($this->getActingUser()->hasRole(Role::MEMBER));
        $this->assertTrue($this->getActingUser()->hasRole(Role::GUEST));

        $response = $this->http('PATCH', '/v1/accounts/'.$this->account->id, []);
        $response->assertStatus(403);
    }
}
