<?php

namespace App\Services\CachingService;

use App\Library\Cache;

class UserCache
{
    public const CACHE_PREFIX = 'user';
    private static function getCacheKey(string $nid)
    {
        return self::CACHE_PREFIX . ":$nid";
    }

    public static function get(string $nid)
    {
        $cacheKey = self::getCacheKey($nid);
        return json_decode(Cache::get($cacheKey), true);
    }

    public static function set(string $nid, array $data): void
    {
        $cacheKey = self::getCacheKey($nid);
        Cache::set($cacheKey, json_encode($data), 60);
    }

    public static function remove(string $nid): void
    {
        $cacheKey = self::getCacheKey($nid);
        Cache::del($cacheKey);
    }
}
