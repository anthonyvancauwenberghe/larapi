<?php

namespace Foundation\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Foundation\Cache\ModelCache;
use Modules\Account\Entities\Account;

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

    public function testCacheSpeed(){
        $model = Account::create(["testthisshit"=>5]);
        \Cache::put('testmodel',$model);

        $time_db_start = microtime(true);
        for($i=0; $i<1000;$i++){
            Account::find($model->id);
        }
        $time_db_end = microtime(true);

        $dbTime = $time_db_end - $time_db_start;

        $time_cache_start = microtime(true);

        for($i=0; $i<1000;$i++){
            \Cache::get('testmodel');
        }
        $time_cache_end = microtime(true);

        $cacheTime = $time_cache_end - $time_cache_start;

        $this->assertGreaterThan($cacheTime, $dbTime);
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
