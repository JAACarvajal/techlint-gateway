<?php

namespace App\Jobs;

use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Arr;

class AuditLogJob implements ShouldQueue
{
    use Queueable;

    /**
     * Audit log action mapper
     *
     * @var array
     */
    private array $auditLogMapper = [
        'auth.login'           => 'login.user',
        'auth.logout'          => 'logout.user',
        'ip-addresses.store'   => 'create.ip_address',
        'ip-addresses.update'  => 'update.ip_address',
        'ip-addresses.destroy' => 'delete.ip_address',
    ];

    /**
     * Map of route names to audit log actions
     *
     * @var array
     */
    private array $exludeChanges = [
        'auth.login',
        'auth.logout',
    ];

    /**
     * exclude attributes from the audit log changes
     *
     * @var array
     */
    private array $excludeAttributes = [
        'user_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Route name
     * @var
     */
    protected string $routeName;

    /**
     * Request data
     * @var
     */
    protected ?array $requestData;

    /**
     * Data to be logged
     * @var
     */
    protected ?array $responseData;

    /**
     * Create a new job instance.
     */
    public function __construct(string $routeName, ?array $requestData = null, ?array $responseData = null)
    {
        $this->routeName = $routeName;
        $this->requestData = $requestData;
        $this->responseData = $responseData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (array_key_exists($this->routeName, $this->auditLogMapper) === false) {
            return;
        }

        AuditLog::create($this->buildAuditPayload());
    }

    /**
     * Build the audit log payload
     *
     * @param string $routeName Route name
     * @param array $responseData Attributes from the response data
     */
    protected function buildAuditPayload(): array
    {
        $authId = Arr::get($this->responseData, 'meta.auth.id');
        [$action, $targetType] = explode('.', $this->auditLogMapper[$this->routeName]);
        $targetId = $this->getTargetId($targetType);

        return [
            'actor_user_id' => $authId,
            'action'        => $action,
            'target_type'   => $targetType,
            'target_id'     => $targetId,
            'changes'       => $this->buildChanges(),
        ];
    }

    /**
     * Build the changes for the audit log
     *
     * @param string $targetId Target ID
     * @param string $action Action
     */
    protected function buildChanges(): ?array
    {
        if (in_array($this->routeName, $this->exludeChanges, true)) {
            return null;
        }

        return $this->getNewAttributes();
    }

    /**
     * Get new attributes from the response data
     * @return array
     */
    protected function getNewAttributes(): array
    {
        return Arr::only(
            Arr::get($this->responseData, 'data.attributes', []),
            array_keys($this->requestData)
        );
    }

    /**
     * Get the target ID from the response data or route
     * @param string $targetType
     */
    protected function getTargetId(string $targetType): ?string
    {
        return Arr::get($this->responseData, 'data.id', null);
    }
}
