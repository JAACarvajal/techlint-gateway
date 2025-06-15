<?php

namespace App\Repositories;

use App\Models\AuditLog;

class AuditLogRepository extends BaseRepository
{
    /**
     * Create ip address repository instance
     *
     * @param AuditLog $model IP address model instance
     */
    public function __construct(AuditLog $model)
    {
        $this->model = $model;
    }
}
