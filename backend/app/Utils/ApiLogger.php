<?php

namespace App\Utils;

class ApiLogger
{
    /**
     * @var array
     */
    private static $api_log = [];

    /**
     * start api log
     */
    public static function startApiLog(): void
    {
        $start_time = microtime(true);

        self::$api_log = [
            'total_time' => 0,
            'total_platform_time' => 0,
            'api_has_error' => 0,
            'input_has_problem' => 0,
            'request_start_time' => $start_time,
            'msisdn' => (int) 0,
            'email' => "",
            'provisioning' => 0,
            'api_types' => '',
            'platforms' => []
        ];
    }

    /**
     * @param string $key
     * @return array|mixed|string
     */
    public static function getApiLog($key = '')
    {
        return ($key == '') ? self::$api_log : (self::$api_log[$key] ?? '');
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public static function addOrUpdateInApiLog($key, $value): void
    {
        self::$api_log[$key] = $value;
    }

    public static function setApiHasError()
    {
        ApiLogger::addOrUpdateInApiLog('api_has_error', 1);
    }

    public static function setInputHasProblem()
    {
        ApiLogger::addOrUpdateInApiLog('input_has_problem', 1);
    }

    /**
     * @param $platform_no
     * @param $key
     * @param $value
     */
    public static function addOrUpdateInPlatformLog($platform_no, $key, $value): void
    {
        if (isset(self::$api_log['platforms'][$platform_no]) && $platform_no >= 0) {
            self::$api_log['platforms'][$platform_no][$key] = $value;

            // if one platform has error, set global api has error
            if ($key == 'platform_has_error' && $value == 1) {
                self::$api_log['api_has_error'] = 1;
            }
        }
    }

    /**
     * @param $platform_no
     */
    public static function setPlatformHasError($platform_no): void
    {
        self::addOrUpdateInPlatformLog($platform_no, 'platform_has_error', 1);
    }

    /**
     * @param $platform_info
     * @param $total_time int microseconds
     * @param int $platform_has_error
     * @param $error_message
     * @return int
     */
    public static function addStaticPlatform($platform_info, $total_time, $platform_has_error = 0, $error_message = '')
    {
        $platform_no = count(self::$api_log['platforms']);
        $static_platform_time = $total_time * 1000 - self::$api_log['total_platform_time'];

        self::$api_log['platforms'][$platform_no] = [
            'api_type' => $platform_info['api_type'] ?? '',
            'api_platform' => $platform_info['api_platform'] ?? 'local',
            'platform_response_time' => round($static_platform_time, 2),
            'platform_status_code' => -1,
            'platform_has_error' => $platform_has_error,
            'error_message' => $error_message,
            'provisioning' => 0
        ];

        return $platform_no;
    }

    /**
     * end api log
     */
    public static function getHeaderLogs()
    {
        $time = microtime(true);

        if (isset(self::$api_log['request_start_time'])) {
            self::$api_log['total_time'] = round(($time - self::$api_log['request_start_time']) * 1000, 2);
            self::$api_log['request_start_time'] = intval(self::$api_log['request_start_time']);
        }

        self::$api_log['request_end_time'] = time();

        if (isset(self::$api_log['platforms']) && count(self::$api_log['platforms']) > 0) {
            self::$api_log['api_types'] = implode(',', array_column(self::$api_log['platforms'], 'api_type'));
        }

        return self::$api_log;
    }

    public static function setCustomTagValue(string $tagName, array $data = [])
    {
        self::$api_log[$tagName] = $data;
    }

}
