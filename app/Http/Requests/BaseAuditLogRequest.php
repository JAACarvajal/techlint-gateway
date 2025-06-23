<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Arr;

abstract class BaseAuditLogRequest extends BaseRequest
{
    /**
     * Allowed attributes for the request
     *
     * @var array
     */
    protected array $allowedAttributes = [
        'actor_user_id',
        'action',
        'target_type',
        'target_id',
        'changes',
    ];

    /**
     * Allowed query parameters for the request
     * @var array
     */
    protected array $allowedQueryParams = [
        'sort',
        'filter',
        'include',
        'rows',
    ];

    /**
     * Map the validated attributes to the allowed attributes
     */
    public function mappedAttributes(): array
    {
        $attributes = $this->input('data.attributes');

        return Arr::only($attributes, $this->allowedAttributes);
    }

    /**
     * Map the validated query parameters to the allowed query parameters
     */
    public function mappedQueryParameters(): array
    {
        $queryParams = $this->validated();

        return Arr::only($queryParams, $this->allowedQueryParams);
    }
}
