<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedDomains = [
            'https://eatplan8.callvcal.com',
            'https://buy.eatplan8.callvcal.com',
            'https://eatplan8.com',
            'https://dashboard.eatplan8.com',
            // Add more domains as needed
        ];

        $origin = $request->header('Origin')??$request->header('origin');
       // Log the incoming request headers
    //    Log::channel('callvcal')->info('Incoming request headers:', $request->header());

       // Proceed with the request handling
       $response = $next($request);

       // Set CORS headers to allow requests from all domains
       $response->headers->set('Access-Control-Allow-Origin', '*');
       $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
       $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

       // Log the updated response headers
    //    Log::channel('callvcal')->info('Updated response headers:', $response->headers->all());

       return $response;
    }
}
