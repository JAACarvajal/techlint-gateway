<?php

namespace App\Services;

use App\Constants\HttpCodes;
use App\Filters\V1\AuditLogFilter;
use App\Http\Resources\AuditLogResource;
use App\Repositories\AuditLogRepository;
use Illuminate\Http\JsonResponse;

class AuditLogService extends BaseService
{
    /**
     * Instance for audig log repository
     * @var
     */
    protected $repository;

    public function __construct(AuditLogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Check if user is authenticated
     */
    public function list(array $filters): JsonResponse
    {
        $auditLogs = $this->repository->paginate(new AuditLogFilter($filters));

        return self::responseSuccess(
            AuditLogResource::collection($auditLogs),
            HttpCodes::OK
        );
    }
}
