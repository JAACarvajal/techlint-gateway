<?php

namespace App\Http\Middleware;

use App\Jobs\AuditLogJob;
use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $routeName = $request->route()?->getName();
        $responseData = json_decode($response->getContent(), true);

        if ($response->isSuccessful() === false) {
            $this->stripMetaFromResponse($response, $responseData);
            return $response;
        }

        AuditLogJob::dispatch($routeName, $responseData);

        $this->stripMetaFromResponse($response, $responseData);

        return $response;
    }

    /**
     * Remove meta information from the response
     *
     * @param Response $response
     * @param array $data
     */
    protected function stripMetaFromResponse(Response $response, ?array $data): void
    {
        if (empty($data) === true) {
            return;
        }

        unset($data['meta']['auth']);

        if (empty($data['meta'] ?? [])) {
            unset($data['meta']);
        }

        $response->setContent(json_encode($data));
    }
}
