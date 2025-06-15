<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseAuditLogRequest;

class ListAuditLogRequest extends BaseAuditLogRequest
{
    /**
     * Required ability for the request
     */
    protected function requiredAbility(): string
    {
        return 'view:audit_log';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'include' => ['sometimes'],
            'filter'  => ['sometimes', 'array'],
            'sort'    => ['sometimes', 'string'],
            'page'    => ['sometimes', 'string']
        ];
    }
}
