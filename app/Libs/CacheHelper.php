<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/06/19
 * Time: 10:18
 */

namespace App\Libs;


class CacheHelper
{

    /**
     * @param $prefix string cache prefix
     * @param $key string cache key
     * @return \stdClass|null
     */
    public static function get($prefix, $key)
    {
        $cacheKey = $prefix . '#' . $key;
        $rawCacheValue = \Cache::get(strtolower($cacheKey));
        return json_decode($rawCacheValue);
    }

    /**
     * @param $prefix string cache prefix
     * @param $key string cache key
     * @param $payload array|string cache payload
     * @param int $age
     * @return \stdClass
     */
    public static function put($prefix, $key, $payload, $age = null)
    {
        if ($age === null) $age = env('CACHE_TIME', 2880);
        $cacheKey = $prefix . '#' . $key;
        $rawCacheValue = json_encode($payload);
        \Cache::put(strtolower($cacheKey), $rawCacheValue, $age);
        return json_decode($rawCacheValue);
    }

    /**
     * @param $prefix string cache prefix
     * @param $key string cache key
     * @return boolean|null
     */
    public static function delete($prefix, $key)
    {
        $cacheKey = $prefix . '#' . $key;
        return \Cache::pull(strtolower($cacheKey));
    }

    /**
     * @param $regex string regex cache key
     * @return int
     */
    public static function removeContains($regex)
    {
        $redis = \Cache::getRedis();
        $keys = $redis->keys($regex);
        $count = 0;
        foreach ($keys as $key) {
            $redis->del($key);
            $count++;
        }
        return $count;
    }
}
