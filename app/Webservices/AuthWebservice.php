<?php

namespace App\Webservices;

use Illuminate\Http\Client\Response;

class AuthWebservice extends BaseWebservice
{
    /**
     * Login user
     *
     * @param array $data Request data
     */
    public function login(array $data): Response
    {
        return $this->post(
            url: $this->getBaseUrl() . '/api/auth/login',
            headers: $this->getHeaders(),
            data: $this->formatRequestBody($data)
        );
    }

    /**
     * Check if the provided token is valid
     */
    public function check(): Response
    {
        return $this->get(
            url: $this->getBaseUrl() . '/api/auth/check',
            headers: $this->getHeaders(),
            token: $this->getToken()
        );
    }

    /**
     * Logout user
     */
    public function logout(): Response
    {
        return $this->post(
            url: $this->getBaseUrl() . '/api/auth/logout',
            headers: $this->getHeaders(),
            token: $this->getToken()
        );
    }

    /**
     * Get the base URL for the authentication service
     */
    public function getBaseUrl(): string
    {
        return env('AUTH_SERVICE_URL');
    }
}
