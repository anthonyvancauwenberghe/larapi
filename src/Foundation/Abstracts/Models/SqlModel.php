<?php

namespace Foundation\Abstracts;

use Foundation\Cache\ModelCache;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MongoModel.
 *
 * @mixin \Eloquent
 */
abstract class SqlModel extends Model
{
    protected $connection = 'mysql';

    public static function find($id, $columns = ['*'])
    {
        if ((bool)config('model.caching')) {
            $model = ModelCache::findOrRequery($id, get_called_class());
            return self::filterFromColumns($model, $columns);
        }
        return self::findWithoutCache($id, $columns);
    }

    public static function findWithoutCache($id, $columns = ['*'])
    {
        return parent::find($id, $columns);
    }

    private static function filterFromColumns($model, $columns)
    {
        if ($columns !== ['*']) {
            return collect($model)->first($columns);
        }
        return $model;
    }

}
