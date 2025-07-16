<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);

            // Log if the response is a 500 error
            if ($response->getStatusCode() >= 500) {
                Log::error('HTTP 500 Error', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'headers' => $request->headers->all(),
                    'input' => $request->all(),
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip(),
                ]);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Middleware Exception', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            throw $e;
        }
    }
}
