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
    private array $auditLogMapper = [
        'auth.login'           => 'login.user',
        'auth.logout'          => 'logout.user',
        'auth.refresh'         => 'refresh.token',
        'ip-addresses.store'   => 'create.ip_address',
        'ip-addresses.update'  => 'update.ip_address',
        'ip-addresses.destroy' => 'delete.ip_address',
        'users.store'          => 'create.user',
    ];

    private array $exludeChanges = [
        'auth.login',
        'auth.logout',
        'auth.check',
        'auth.refresh'
    ];

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

        if ($this->shouldLog($response, $routeName) === false) {
            $this->stripMetaFromResponse($response, $responseData);
            return $response;
        }

        $payload = $this->buildAuditPayload($routeName, $responseData);

        AuditLogJob::dispatch($payload);

        $this->stripMetaFromResponse($response, $responseData);

        return $response;
    }

    /**
     * Check if the action should be logged
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param mixed $routeName
     */
    protected function shouldLog(Response $response, ?string $routeName): bool
    {
        return $response->isSuccessful() && array_key_exists($routeName, $this->auditLogMapper);
    }

    /**
     * Build the audit log payload
     *
     * @param Request $request Request instance
     * @param string $routeName Route name
     * @param mixed $authId Auth user ID
     * @param array $attributes Attributes from the response data
     */
    protected function buildAuditPayload(string $routeName, ?array $responseData): array
    {
        $authId = Arr::get($responseData, 'meta.auth.id');
        $attributes = Arr::get($responseData, 'data.attributes', []);
        [$action, $targetType] = explode('.', $this->auditLogMapper[$routeName]);
        $targetId = Arr::get($responseData, 'data.id', null) ?? request()->route($targetType);

        return [
            'actor_user_id' => $authId,
            'action'        => $action,
            'target_type'   => $targetType,
            'target_id'     => $targetId,
            'changes'       => $this->buildChanges($routeName, $attributes, $targetId),
        ];
    }

    /**
     * Build the changes for the audit log
     *
     * @param string $routeName
     * @param array $attributes
     * @param string $targetId
     * @return bool|string
     */
    protected function buildChanges(string $routeName, array $attributes, ?string $targetId): string
    {
        if (in_array($routeName, $this->exludeChanges, true)) {
            return '{}';
        }

        $latestAudit = AuditLog::where('target_id', $targetId)
            ->latest('created_at')
            ->first();

        $oldAttributes = $latestAudit
            ? Arr::get(json_decode($latestAudit->changes, true), 'new', [])
            : [];

        $changes = [
            'old' => $oldAttributes,
            'new' => $attributes,
        ];

        return json_encode($changes);
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
