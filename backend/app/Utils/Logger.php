<?php

namespace App\Utils;

class Logger
{
    /**
     * @param $channel_name
     * @param $log_type
     * @param $message
     * @param array $context
     *
     * @return bool
     */
    public static function log($channel_name, $log_type, $message, $context = [], $allowIndexing = false)
    {
        try {
            $context['extra'] = [
                'requestID' => $_SERVER['HTTP_X_REQUEST_ID'] ?? '',
                'process' => $channel_name
            ];

            $channel = config('logging.default');

            if ($allowIndexing === true) {
                $channel = config('logging.custom_log_channel');
            }

            app('log')->channel($channel)->{$log_type}($message, $context);

            return true;
        } catch (\Throwable $exception) {
            return false;
        }
    }
}
