<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'ip_address',
        'user_agent',
        'request_url',
    ];

    /**
     * The attributes that should be cast to native types
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime'
    ];
}
