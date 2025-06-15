<?php

namespace App\Http\Middleware;

use Closure;
use App\Webservices\AuthWebservice;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\{JsonResponse, Request};
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Auth web service instance
     *
     * @var AuthWebservice
     */
    public AuthWebservice $authWebservice;

    /**
     * Create a new middleware instance
     *
     * @param AuthWebservice  $authWebservice auth web service instance
     */
    public function __construct(AuthWebservice $authWebservice)
    {
        $this->authWebservice = $authWebservice;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        if (empty($request->bearerToken()) === true) {
            return response()->json(['message' => 'Unauthorized'], HttpCodes::UNAUTHORIZED);
        }

        $response = $this->authWebservice->check();

        if ($response->unauthorized() === true) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        $request->attributes->set('user_id', $response->json('data.id'));
        $request->attributes->set('user_attributes', $response->json('data.attributes'));

        return $next($request);
    }
}
