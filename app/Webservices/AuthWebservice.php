<?php

namespace App\Webservices;

class AuthWebservice extends BaseWebservice
{
    /**
     * Login user
     *
     * @param string $token JWT token to check
     */
    public function login(array $data)
    {
        return $this->post(url: $this->getBaseUrl() . '/api/auth/login', headers: $this->getHeaders(), data: $this->formatRequestBody($data));
    }

    /**
     * Check if the provided token is valid
     *
     * @param string $token JWT token to check
     */
    public function check()
    {
        return $this->get(url: $this->getBaseUrl() . '/api/auth/check', headers: $this->getHeaders(), token: $this->getToken());
    }

    /**
     * Logout user
     *
     * @param string $token JWT token
     */
    public function logout()
    {
        return $this->post(url: $this->getBaseUrl() . '/api/auth/logout', headers: $this->getHeaders(), token: $this->getToken());
    }

    /**
     * Get the base URL for the authentication service
     */
    public function getBaseUrl(): string
    {
        return env('AUTH_SERVICE_URL');
    }
}
