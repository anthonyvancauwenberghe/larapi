<?php

namespace Foundation\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Foundation\Cache\ModelCache;

class CacheObserverTest extends TestCase
{
    public function testCache()
    {
        $user = $this->createUser();
        $user->fresh();
        unset($user->role_ids);
        $this->assertEquals($user->toArray(), $user::cache()->find($user->id, false)->toArray());
        $this->assertEquals($user->toArray(), $user::cache()->findBy('identity_id', $user->identity_id, false)->toArray());
    }

    public function testClearModelsCache()
    {
        $user = $this->createUser();

        $this->assertNotNull($user::cache()->find($user->id));
        ModelCache::clearAll();
        $this->assertNull($user::cache()->find($user->id));
    }

    public function testClearSpecificModelCache()
    {
        $user = $this->createUser();
        $this->assertNotNull($user::cache()->find($user->id));
        $user::cache()->clearModelCache();
        $this->assertNull($user::cache()->find($user->id));
    }
}
