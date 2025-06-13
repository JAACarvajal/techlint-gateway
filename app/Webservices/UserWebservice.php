<?php

namespace App\Webservices;

use Illuminate\Http\Client\Response;

class UserWebservice extends BaseWebservice
{
    /**
     * Create a new user
     *
     * @param array $data Data to create a new user
     */
    public function create(array $data): Response
    {
        return $this->post(
            $this->getBaseUrl() . '/api/v1/users',
            $this->getHeaders(),
            $this->formatRequestBody($data),
            $this->getToken()
        );
    }

    /**
     * Get the base URL for the auth service
     */
    public function getBaseUrl(): string
    {
        return env('AUTH_SERVICE_URL');
    }
}
