<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        $csp = "default-src 'self'; " .
               "script-src 'self' https://cdn.tiny.cloud 'unsafe-inline'; " .
               "style-src 'self' 'unsafe-inline'; " .
               "img-src 'self' data: blob:; " .
               "font-src 'self'; " .
               "connect-src 'self'; " .
               "frame-src 'self'; " .
               "object-src 'none'; " .
               "base-uri 'self'";
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
