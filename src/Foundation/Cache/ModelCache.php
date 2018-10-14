<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.10.18
 * Time: 14:11.
 */

namespace Foundation\Cache;

use Cache;
use Foundation\Exceptions\Exception;
use Illuminate\Support\Facades\Redis;

class ModelCache
{
    /**
     * @param $id
     * @param string $modelClass
     *
     * @return mixed
     */
    public static function find($id, $modelClass)
    {
        return Cache::get(self::getCacheName($id, $modelClass));

    }

    public static function findOrRequery($id, $modelClass)
    {
        return Cache::remember(self::getCacheName($id, $modelClass), self::getCacheTime(),function () use ($id, $modelClass) {
            return $modelClass::findWithoutCache($id);
        });
    }

    /**
     * @param string
     */
    public static function getCacheName($id, $modelClass)
    {
        return config('model.cache_prefix') . ':' . strtolower(get_short_class_name($modelClass)) . ':' . $id;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    private static function getCacheTime()
    {
        return config('model.cache_time');
    }

    /**
     * @param $id
     * @param string $modelClass
     *
     * @return mixed
     */
    public static function findWithoutRequery($id, $modelClass)
    {
        return Cache::get(self::getCacheName($id, $modelClass));
    }

    /**
     * @param \Eloquent $model
     */
    public static function store($model)
    {
        Cache::put(self::getCacheName($model->getKey(), $model), $model->fresh(), self::getCacheTime());
    }

    /**
     * @param $id
     * @param string $modelClass
     *
     * @return bool
     */
    public static function remove($id, $modelClass)
    {
        return Cache::forget(self::getCacheName($id, $modelClass));
    }

    public static function clearAll()
    {
        $pattern = config('model.cache_prefix');
        self::deleteWithPrefix($pattern);
    }

    /**
     * @param $prefix
     *
     * @throws Exception
     */
    private static function deleteWithPrefix($prefix)
    {
        $redis = self::getCacheConnection();
        $keyPattern = Cache::getPrefix() . $prefix . '*';
        $keys = $redis->keys($keyPattern);
        $redis->delete($keys);
    }

    /**
     * @throws Exception
     *
     * @return \Illuminate\Redis\Connections\Connection
     */
    private static function getCacheConnection()
    {
        if (config('cache.default') === 'redis') {
            return Redis::connection('cache');
        }

        throw new Exception('This action is only possible with redis as cache driver');
    }

    /**
     * @param $modelClass
     *
     * @throws Exception
     */
    public static function clearModel($modelClass)
    {
        $pattern = config('model.cache_prefix') . ':' . strtolower(get_short_class_name($modelClass));
        self::deleteWithPrefix($pattern);
    }
}
