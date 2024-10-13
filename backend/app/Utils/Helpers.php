<?php

namespace App\Utils;

use App\Utils\Logger;

class Helpers
{
    /**
     * @param \Throwable $exception
     *
     * @return string
     */
    public static function formatErrorLogMessage(\Throwable $exception)
    {
        $message = 'Error occured in File: ' . $exception->getFile();
        $message .= ' on Line: ' . $exception->getLine();
        $message .= ' due to: ' . $exception->getMessage();

        return $message;
    }

    /**
     * @param mixed $message
     *
     * @return mixed
     */
    private static function prepareErrorMessage($message)
    {
        if ($message instanceof \Throwable) {
            return self::formatErrorLogMessage($message);
        } elseif (!is_string($message)) {
            return json_encode($message);
        }

        return $message;
    }

    /**
     * @param string $process
     * @param string $type
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public static function writeToLog($process, $type, $message, $context = [], $allowIndexing = false)
    {
        Logger::log($process, $type, self::prepareErrorMessage($message), $context, $allowIndexing);
    }

    public static function trimArrayValues(array $array, bool $toLowercase = false)
    {
        return array_map(function ($element) use ($toLowercase) {
            return $toLowercase === true ? strtolower(trim($element)) : trim($element);
        }, $array);
    }

    public static function convertStringToArray(string $string, bool $toLowercase = false, string $seperator = ',')
    {
        $array = explode($seperator, $string);

        return self::trimArrayValues($array, $toLowercase);
    }

}
