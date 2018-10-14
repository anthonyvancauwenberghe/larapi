<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.10.18
 * Time: 13:31.
 */

namespace Foundation\Observers;

use Foundation\Abstracts\Observers\Observer;
use Foundation\Cache\ModelCache;

class CacheObserver extends Observer
{
    /**
     * @param \Eloquent $model
     */
    public function created($model)
    {
        ModelCache::store($model);
    }

    /**
     * @param \Eloquent $model
     */
    public function updated($model)
    {
        ModelCache::store($model);
    }

    /**
     * @param \Eloquent $model
     */
    public function deleted($model)
    {
        ModelCache::remove($model->getKey(), $model);
    }
}
