<?php

namespace App\Models;

use App\Filters\V1\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AuditLog extends Model
{
    /**
     * Fillable attributes for the audit log
     *
     * @var array
     */
    protected $fillable = [
        'actor_user_id',
        'action',
        'target_type',
        'target_id',
        'changes',
    ];

    /**
     * The attributes that should be cast to native types
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'string',
        'actor_user_id' => 'string',
        'action'        => 'string',
        'target_type'   => 'string',
        'target_id'     => 'string',
        'changes'       => 'array',
        'timestamp'     => 'datetime'
    ];

    /**
     * Boot method to set up model event listeners
     */
    public static function booted()
    {
        // Prevent modification of audit logs
        static::updating(function () {
            throw new \Exception('Audit logs are immutable.');
        });

        // Prevent deletion of audit logs
        static::deleting(function () {
            throw new \Exception('Audit logs cannot be deleted.');
        });
    }

    /**
     * Scope to apply filters to the Eloquent query builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder Eloquent query builder instance
     * @param \App\Filters\V1\Filter $filters Filters instance containing the filters to apply
     */
    public function scopeFilter(Builder $builder, Filter $filters): Builder
    {
        return $filters->apply($builder);
    }
}
