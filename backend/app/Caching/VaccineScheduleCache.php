<?php

namespace App\Caching;

use App\Library\Cache;

class VaccineScheduleCache
{
    public const CACHE_PREFIX = 'vaccine_schedule';
    private static function getCacheKey(int $vaccineCenterId, string $scheduledDate)
    {
        return self::CACHE_PREFIX . ":$vaccineCenterId:$scheduledDate";
    }

    public static function get(int $vaccineCenterId, string $scheduledDate)
    {
        $cacheKey = self::getCacheKey($vaccineCenterId, $scheduledDate);
        return json_decode(Cache::get($cacheKey), true);
    }

    public static function set(int $vaccineCenterId, string $scheduledDate, array $data): void
    {
        $cacheKey = self::getCacheKey($vaccineCenterId, $scheduledDate);
        Cache::set($cacheKey, json_encode($data), 60);
    }

    public static function remove(int $vaccineCenterId, string $scheduledDate): void
    {
        $cacheKey = self::getCacheKey($vaccineCenterId, $scheduledDate);
        Cache::del($cacheKey);
    }
}
