<?php

namespace App\Utils;

use App\Utils\ApiLogger;

class ResponseDecorator
{
    public static function json($response, $httpStatusCode = 200, $headers = [])
    {
        match ($httpStatusCode) {
            400, 404, 422, 409 => ApiLogger::setInputHasProblem(),
            500 => ApiLogger::setApiHasError(),
            default => 'OK'
        };

        return response()->json($response, $httpStatusCode)->withHeaders(self::prepareHeader($headers));
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    private static function prepareHeader(array $headers = []): array
    {
        return array_merge(self::getLogHeaders(), $headers);
    }

    /**
     * Get API Loggers and send with response
     *
     * @return array
     */
    private static function getLogHeaders(): array
    {
        return [
            'Api-Log' => json_encode(ApiLogger::getHeaderLogs())
        ];
    }
}
