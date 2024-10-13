<?php

namespace App\Http\Middleware;

use App\Utils\ApiLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class CommonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        ApiLogger::startApiLog();

        $response = $next($request);

        if (in_array($response->status(), [HttpResponse::HTTP_UNPROCESSABLE_ENTITY, Response::HTTP_INTERNAL_SERVER_ERROR])) {
            \App\Utils\Helpers::writeToLog('ERROR_REQUEST_PAYLOAD', 'debug', 'endpoint: ' . $request->path());
        }

        return $response;
    }
}
