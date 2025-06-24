<?php

namespace App\Filters;
use Illuminate\Database\Eloquent\Builder;

class AuditLogFilter extends Filter
{
    /**
     * List of sortable fields
     * @var array
     */
    protected $sortables = [
        'action',
        'label',
        'actorUserId' => 'actor_user_id',
        'targetType' => 'target_type',
        'targetId' => 'target_id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    /**
     * Filter by action
     * @param string $value
     */
    public function action($value): Builder
    {
        return $this->builder->where('action', $value);
    }

    /**
     * Filter by actorUserId
     * @param string $value
     */
    public function actorUserId($value): Builder
    {
        return $this->builder->where('actor_user_id', $value);
    }

    /**
     * Filter by created_at datetime
     * @param string $value
     */
    public function createdAt($value): Builder
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    /**
     * Include related models
     * @param string $value
     */
    public function include($value): Builder
    {
        return $this->builder->with($value);
    }

    /**
     * Filter by target_type
     * @param string $value
     */
    public function targetType($value): Builder
    {
        return $this->builder->where('target_type', $value);
    }

    /**
     * Filter by target_id
     * @param string $value
     */
    public function targetId($value): Builder
    {
        return $this->builder->where('target_id', $value);
    }

    /**
     * Filter by updated_at datetime
     * @param string $value
     */
    public function updatedAt($value): Builder
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
