<?php

namespace Foundation\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Foundation\Cache\ModelCache;
use Modules\User\Entities\User;

class CacheObserverTest extends TestCase
{
    public function testCache()
    {
        $user = $this->createUser();
        $this->assertArraySubset($user->toArray(), ModelCache::find($user->getKey(), User::class)->toArray());
    }

    public function testClearModelsCache()
    {
        $user = $this->createUser();

        $this->assertNotNull(ModelCache::find($user->getKey(), User::class));
        ModelCache::clearAll();
        $this->assertEquals(null, ModelCache::findWithoutRequery($user->getKey(), User::class));
    }

    public function testClearSpecificModelCache()
    {
        $user = $this->createUser();

        $this->assertNotNull(ModelCache::find($user->getKey(), User::class));
        ModelCache::clearModel($user);
        $this->assertEquals(null, ModelCache::findWithoutRequery($user->getKey(), User::class));
    }
}
