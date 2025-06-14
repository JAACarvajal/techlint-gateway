<?php

namespace App\Http\Middleware;

use App\Jobs\AuditLogJob;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    private array $auditLogMapper = [
        'auth.login'           => 'login.user',
        'auth.logout'          => 'logout.user',
        'auth.refresh'         => 'refresh.token',
        'auth.check'           => 'check.token',
        'ip-addresses.index'   => 'list.ip_addresses',
        'ip-addresses.store'   => 'create.ip_address',
        'ip-addresses.update'  => 'update.ip_address',
        'ip-addresses.destroy' => 'delete.ip_address',
        'users.store'          => 'create.user',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $routeName = $request->route()->getName();
        $target = explode('.', $this->auditLogMapper[$routeName]);

        if ($response->isSuccessful() && in_array( $routeName, $this->auditLogMapper)) {
            AuditLogJob::dispatch([
                'actor_user_id' => 0,
                'action'        => $target[0],
                'target_type'   => $target[1],
                'target_id'     => null,
                'changes'       => [],
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
                'request_url'   => $request->fullUrl(),
            ]);
        }

        return $response;
    }
}
