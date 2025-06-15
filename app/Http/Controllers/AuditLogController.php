<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListAuditLogRequest;
use Illuminate\Http\Request;
use App\Services\AuditLogService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuditLogController extends Controller
{

    /**
     * Ip addresss service instance
     * @var
     */
    private $service;

    /**
     * Create ip address controller instance
     *
     * @param AuditLogService $service Ip address service instance
     */
    public function __construct(AuditLogService $service)
    {
        $this->service = $service;
    }

    /**
     * List IP addresses with optional filters
     *
     * @param Request $request Request instance
     */
    public function index(ListAuditLogRequest $request): JsonResponse
    {
        return $this->service->list($request->mappedQueryParameters());
    }
}
