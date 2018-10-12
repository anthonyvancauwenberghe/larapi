<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.10.18
 * Time: 21:56.
 */

namespace Foundation\Abstracts\Tests;

use Foundation\Traits\RefreshDatabaseBeforeTest;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\User\Entities\User;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabaseBeforeTest, CreatesApplication;

    protected function createUser()
    {
        return factory(User::class)->create();
    }

    protected function actAsRandomUser()
    {
        $user = $this->createUser();
        $this->actingAs($user);

        return $user;
    }
}
