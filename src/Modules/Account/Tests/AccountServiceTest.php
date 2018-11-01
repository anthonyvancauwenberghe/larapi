<?php

namespace Modules\Account\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Modules\Account\Entities\Account;
use Modules\User\Entities\User;

class AccountServiceTest extends TestCase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Account[]
     */
    protected $accounts;

    protected function seedData()
    {
        parent::seedData();
        $this->user = $this->actAsRandomUser();
        $this->accounts = Account::fromFactory(5)->create(['user_id' => $this->user->id]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserAccounts()
    {
        $Accounts = $this->accounts->toArray();

        $this->assertNotEmpty($Accounts);
    }

    public function testAccountBelongsToUser()
    {
        $Account = Account::first();
        $user = $Account->user;
        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $Account->user);
    }
}
