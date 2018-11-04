<?php

namespace Modules\Account\Tests;

use Foundation\Abstracts\Tests\HttpTest;
use Modules\Account\Contracts\AccountServiceContract;
use Modules\Account\Entities\Account;
use Modules\Account\Services\AccountService;
use Modules\Account\Transformers\AccountTransformer;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Entities\Machine;
use Modules\User\Entities\User;

class AccountHttpTest extends HttpTest
{
    protected $roles = Role::USER;

    /**
     * @var User
     */
    protected $user;

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
        $this->user = $this->getHttpUser();
        $this->machine = Machine::fromFactory()->create(['user_id' => $this->user->id]);
        $this->account = factory(Account::class)->create(['machine_id' => $this->machine->id, 'user_id' => $this->user->id]);

        $this->service = $this->app->make(AccountServiceContract::class);
    }

    public function testAllAccounts()
    {
        $this->user->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/accounts');
        $this->assertEquals(
            AccountTransformer::collection($this->service->getByUserId($this->user->id))->serialize(),
            $this->decodeHttpResponse($response)
        );
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindAccount()
    {
        $this->user->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/accounts/' . $this->account->id);
        $response->assertStatus(200);

        $this->user->syncRoles(Role::GUEST);
        $response = $this->http('GET', '/v1/accounts/' . $this->account->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindAccountWithRelations()
    {
        $this->user->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/accounts/' . $this->account->id, ['include' => 'user,machine']);
        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $this->decodeHttpResponse($response));
        $this->assertArrayHasKey('machine', $this->decodeHttpResponse($response));

        $response = $this->http('GET', '/v1/accounts/' . $this->account->id);
        $response->assertStatus(200);
        $this->assertArrayNotHasKey('user', $this->decodeHttpResponse($response));
    }

    public function testUnauthorizedAccessAccount()
    {
        $user = factory(User::class)->create();
        $Account = factory(Account::class)->create(['user_id' => $user->id]);
        $this->user->syncRoles(Role::USER);
        $response = $this->http('GET', '/v1/accounts/' . $Account->id);
        $response->assertStatus(403);
    }

    public function testUnauthorizedDeleteAccount()
    {
        $user = factory(User::class)->create();
        $Account = factory(Account::class)->create(['user_id' => $user->id]);
        $this->user->syncRoles(Role::USER);
        $response = $this->http('DELETE', '/v1/accounts/' . $Account->id);
        $response->assertStatus(403);
    }

    public function testDeleteAccount()
    {
        $this->user->syncRoles(Role::USER);
        $response = $this->http('DELETE', '/v1/accounts/' . $this->account->id);
        $response->assertStatus(204);
    }

    public function testAdminAccessAccount()
    {
        $user = factory(User::class)->create();
        $Account = factory(Account::class)->create(['user_id' => $user->id]);

        $this->user->syncRoles(Role::ADMIN);
        $response = $this->http('GET', '/v1/accounts/' . $Account->id);
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateAccount()
    {
        $this->user->syncRoles(Role::USER);
        $account = Account::fromFactory()->make([
            'user_id' => $this->user->id,
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
        $this->user->syncRoles(Role::USER);
        /* Test response for a normal user */
        $response = $this->http('PATCH', '/v1/accounts/' . $this->account->id, []);
        $response->assertStatus(200);

        /* Test response for a guest user */
        $this->user->syncRoles(Role::GUEST);
        $this->assertFalse($this->user->hasRole(Role::USER));
        $this->assertTrue($this->user->hasRole(Role::GUEST));

        $response = $this->http('PATCH', '/v1/accounts/' . $this->account->id, []);
        $response->assertStatus(403);
    }
}
