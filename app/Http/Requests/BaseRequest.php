<?php

namespace App\Http\Requests;

use App\Concerns\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    use ApiResponse;

    /**
     * Get the validation rules that apply to the request.
     */
    abstract protected function requiredAbility(): string;

    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return $this->hasPermission();
    }

    /**
     * Handle a failed authorization attempt
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization(): never
    {
        throw new AuthorizationException('This action is unauthorized.');
    }

    /**
     * Get the authenticated user ID
     */
    public function getAuthUserId(): ?string
    {
        return $this->attributes->get('user_id');
    }

    /**
     * Get user attributes from the request
     */
    public function getUserAttributes(): array
    {
        return $this->attributes->get('user_attributes');
    }

    /**
     * Check if the user has the required permission
     */
    public function hasPermission(): bool
    {
        return in_array($this->requiredAbility(), $this->getUserAttributes()['permissions']);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->getUserAttributes()['is_admin'] === true;
    }
}
