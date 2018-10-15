<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.10.18
 * Time: 23:33.
 */

namespace Foundation\Traits;


use DB;
use Foundation\Cache\ModelCache;
use Illuminate\Contracts\Support\Arrayable;

trait Cacheable
{
    public static function find($id, $columns = ['*'])
    {
        if ((bool)config('model.caching')) {
            $model = ModelCache::findOrRequery($id, get_called_class());
            return self::filterFromColumns($model, $columns);
        }
        return static::findWithoutCache($id, $columns);
    }

    public static function findWithoutCache($id, $columns = ['*'])
    {
        $model = new static();
        if (is_array($id) || $id instanceof Arrayable) {
            return $model::whereIn($model->getKeyName(), $id)->get($columns);
        }
        return $model::whereKey($id)->first($columns);
    }

    private static function filterFromColumns($model, $columns)
    {
        if ($columns !== ['*']) {
            return collect($model)->first($columns);
        }
        return $model;
    }
}
