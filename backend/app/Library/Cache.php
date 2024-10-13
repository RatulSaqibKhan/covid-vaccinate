<?php

namespace App\Library;

use App\Utils\Helpers;
use Predis\Client;
use Predis\Collection\Iterator;

class Cache
{
    /**
     * Library used: Predis
     * Github: https://github.com/predis/predis
     * Documentation: https://squizzle.me/php/predis/doc/Commands
     */
    private static $cache = null;
    private static $cacheServerErrorMessage = "Redis server error";

    /**
     * Initialize cache with cache client
     *
     * @return void
     */
    private static function initialize(): void
    {
        if (is_null(self::$cache)) {
            self::$cache = new Client([
                'host' => env('REDIS_HOST'),
                'password' => env('REDIS_PASSWORD'),
                'port' => env('REDIS_PORT'),
                'database' => env('REDIS_CACHE_DB', '1'),
            ]);
        }
    }

    /**
     * Initialize redis.
     *
     * @return void
     */
    public function __construct()
    {
        self::initialize();
    }

    /**
     * Check if a key exists in cache
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return self::exists($key);
    }

    /**
     * Check if a key exists in cache
     *
     * @param string $key
     * @return bool
     */
    public static function exists(string $key): bool
    {
        $exists = false;
        try {
            self::initialize();
            $exists = self::$cache->exists($key) == 1;
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        } finally {
            return $exists;
        }
    }
    /**
     * Sets multiple hash fields to multiple values
     *
     * Previous name setArray
     *
     * @param string $key Redis key name
     * @param array $data Key data
     * @param int $lifetime, default no expiry
     */
    public static function hmset($key, $data, $lifetime = null)
    {
        try {
            self::initialize();
            self::$cache->hmset($key, $data);
            if ($lifetime) {
                self::$cache->expire($key, $lifetime * 60);
            }
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        }
    }

    /**
     * Gets all the fields and values stored in a
     * hash at the specified key.
     *
     * Previous name getArray
     *
     * @param string $key Redis key name
     * @return array
     */
    public static function hgetall($key)
    {
        $data = [];
        try {
            self::initialize();
            $data = self::$cache->hgetall($key);
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        } finally {
            return $data;
        }
    }

    /**
     * Sets only one value by one field name with REDIS key.
     *
     * Previous name setKeyField
     *
     * @param string $key Redis key name
     * @param string $fieldName Field name
     * @param string $value Value
     * @return void
     */
    public static function hset($key, $fieldName, $value)
    {
        try {
            self::initialize();
            self::$cache->hset($key, $fieldName, $value);
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        }
    }

    /**
     * Get value by field name with REDIS key.
     *
     * Previous name getKeyFieldValue
     *
     * @param string $key Redis key name
     * @param string $fieldName Field name
     * @return string
     */
    public static function hget($key, $fieldName)
    {
        $data = null;
        try {
            self::initialize();
            $data = self::$cache->hget($key, $fieldName);
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        } finally {
            return $data;
        }
    }

    /**
     * Remove a hash stored key
     *
     *
     * @param string $key Redis key name
     * @param string|Array $fieldName Field name
     * @return integer success returns 1, fail 0
     */
    public static function hdel($key, $fieldName)
    {
        $deleted = 0;
        try {
            self::initialize();
            $deleted = self::$cache->hdel($key, $fieldName);
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        } finally {
            return $deleted;
        }
    }

    /**
     * Sets multiple hash fields to multiple values
     * with 15 minutes Expiry Time.
     *
     * Previous name setArrayExpiry
     *
     * @param string $key Redis key name
     * @param array $data Key data
     * @return void
     */
    public static function multipleHmset($key, $data, $lifetime = 15)
    {
        try {
            self::initialize();
            self::$cache->pipeline(function ($pipe) use ($key, $data, $lifetime) {
                $pipe->hmset($key, $data);
                $pipe->expire($key, $lifetime * 60);
            });
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        }
    }

    /**
     * Set value against single key without any expiry.
     *
     * Previous name setKey
     *
     * @param string $key Redis key name
     * @param string $value Key value
     * @return bool
     *
     */
    public static function forever(string $key, $value) {
        return self::set($key, $value);
    }

    /**
     * Set value against single key
     * with Expiry Time.
     *
     * Previous name setKey
     *
     * @param string $key Redis key name
     * @param string $value Key value
     * @param int $lifetime
     * @return bool
     *
     */
    public static function set(string $key, $value, int $lifetime = 0)
    {
        $response = false;
        try {
            self::initialize();
            self::$cache->set($key, $value);
            if ($lifetime > 0) {
                self::$cache->expire($key, $lifetime * 60);
            }
            $response = true;
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        } finally {
            return $response;
        }
    }

    /**
     * Get value against single key
     * with Expiry Time.
     *
     * Previous name getKey
     *
     * @param string $key Redis key name
     * @return string
     *
     */
    public static function get($key)
    {
        $data = null;
        try {
            self::initialize();
            $data = self::$cache->get($key);
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        } finally {
            return $data;
        }
    }

    /**
     * Finds all keys matching the specified
     * pattern in redis database.
     *
     * Previous name keys
     *
     * @param string $pattern Search patterns i.e. "channel_*" or "channel_name".
     * @return array
     */
    public static function keys($pattern)
    {
        $data = [];
        try {
            self::initialize();
            $data = self::$cache->keys($pattern);
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        } finally {
            return $data;
        }
    }


    /**
     * Remove key if found
     * key in redis database.
     *
     * Previous name remove
     *
     * @param array $keys find and del keys i.e. 'key1', 'key2' or ['key1', 'key2'].
     * @return int
     *
     */
    public static function del($keys)
    {
        $data = 0;
        try {
            self::initialize();
            if ($keys) {
                $data = self::$cache->del($keys);
            }
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        } finally {
            return $data;
        }
    }

    /**
     * SCAN implementation
     * https://github.com/predis/predis/blob/v1.0/examples/redis_collections_iterators.php
     *
     * Previous name scan
     *
     */
    public static function scan($pattern)
    {
        $keys = [];
        try {
            self::initialize();
            foreach (new Iterator\Keyspace(self::$cache, $pattern) as $key) {
                $keys[] = $key;
            }
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            Helpers::writeToLog('CACHE_ERROR', 'error', self::$cacheServerErrorMessage, [__METHOD__, $errMessage]);
        } finally {
            return $keys;
        }
    }
}
