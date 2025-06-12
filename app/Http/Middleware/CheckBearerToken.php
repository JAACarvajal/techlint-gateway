<?php

namespace App\Http\Middleware;

use App\Constants\HttpCodes;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (empty($request->bearerToken()) === true) {
            return response()->json(['message' => 'Unauthorized'], HttpCodes::UNAUTHORIZED);
        }

        return $next($request);
    }
}
