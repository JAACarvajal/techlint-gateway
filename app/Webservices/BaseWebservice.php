<?php

namespace App\Webservices;

use App\Concerns\HttpClient;
use App\Interfaces\WebserviceInterface;

abstract class BaseWebservice implements WebserviceInterface
{
    use HttpClient;

    /**
     * Get the headers to be used in requests
     */
    public function getHeaders(): array
    {
        return [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    public function formatRequestBody(array $data): array
    {
        return [
            'data' => [
                'attributes' => $data
            ]
        ];
    }

    public function getToken(): string
    {
        return request()->bearerToken() ?? '';
    }
}
