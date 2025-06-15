<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'audit_log',
            'id'   => $this->id,
            'attributes' => [
                'actor_user_id' => $this->actor_user_id,
                'action'        => $this->action,
                'target_type'   => $this->target_type,
                'target_id'     => $this->target_id,
                'changes'       => $this->changes,
                $this->mergeWhen(
                    $request->routeIs('audit-log.*'),
                    [
                        'created_at' => $this->created_at,
                        'updated_at' => $this->updated_at
                    ]
                ),
            ],
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request $request Request instance
     */
    public function with(Request $request): array
    {
        return [
            'meta' => $this->withAuthMetadata($request),
        ];
    }
}
