<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.10.18
 * Time: 21:56.
 */

namespace Foundation\Abstracts\Tests;

use Foundation\Traits\RefreshDatabase;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\User\Entities\User;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, CreatesApplication;

    protected function createUser()
    {
        return factory(User::class)->create();
    }

    protected function actAsRandomUser()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $user = $this->createUser();
        } else {
            $user = $users->random();
        }

        $this->actingAs($user);

        return $user;
    }
}
