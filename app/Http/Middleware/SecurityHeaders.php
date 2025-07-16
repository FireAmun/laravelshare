<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', config('security.headers.x_frame_options', 'DENY'));
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', config('security.headers.referrer_policy', 'strict-origin-when-cross-origin'));
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // HSTS Header (only for HTTPS)
        if ($request->isSecure()) {
            $maxAge = config('security.headers.hsts_max_age', 31536000);
            $response->headers->set('Strict-Transport-Security', "max-age={$maxAge}; includeSubDomains; preload");
        }

        // Content Security Policy
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://unpkg.com",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdnjs.cloudflare.com",
            "font-src 'self' https://fonts.bunny.net https://cdnjs.cloudflare.com",
            "img-src 'self' data: https:",
            "connect-src 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'"
        ];
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        return $response;
    }
}
