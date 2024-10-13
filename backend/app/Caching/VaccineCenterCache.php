<?php

namespace App\Caching;

use App\Library\Cache;

class VaccineCenterCache
{
    public const CACHE_PREFIX = 'vaccine_center';
    private static function getCacheKey(string $name)
    {
        return self::CACHE_PREFIX . ":$name";
    }

    public static function get(string $name)
    {
        $cacheKey = self::getCacheKey($name);
        return json_decode(Cache::get($cacheKey), true);
    }

    public static function set(string $name, array $data): void
    {
        $cacheKey = self::getCacheKey($name);
        Cache::set($cacheKey, json_encode($data), 60);
    }

    public static function remove(string $name): void
    {
        $cacheKey = self::getCacheKey($name);
        Cache::del($cacheKey);
    }

    public static function redisPipelineStore($data)
    {
        $haveToStore = [];
        $expireTime = 60 * 60;

        foreach ($data as $vc) {
            $name = $vc->name;
            $key = self::getCacheKey($name);

            $haveToStore[$key] = $vc;
        }

        Cache::pipelineSet(function ($pipe) use ($haveToStore, $expireTime) {
            foreach ($haveToStore as $key => $value) {
                $pipe->set($key, serialize($value));
                $pipe->expire($key, $expireTime);
            }
        });
    }
}
