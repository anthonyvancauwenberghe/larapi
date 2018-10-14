<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.10.18
 * Time: 23:33.
 */

namespace Foundation\Traits;


use Foundation\Cache\ModelCache;

trait Cacheable
{
    public static function find($id, $columns = ['*'])
    {
        $model = ModelCache::findOrRequery($id, get_called_class());
        return self::filterFromColumns($model, $columns);
    }

    public static function findWithoutCache($id, $columns = ['*'])
    {
        return get_parent_class(self::class)::find($id, $columns);
    }

    private static function filterFromColumns($model, $columns)
    {
        if ($columns !== ['*']) {
            return collect($model)->first($columns);
        }
        return $model;
    }
}
