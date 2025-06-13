<?php

namespace App\Webservices;

use Illuminate\Http\Client\Response;

class IpAddressWebservice extends BaseWebservice
{
    /**
     * Create a new IP address
     *
     * @param array $data Data to create a new IP address
     */
    public function create(array $data): Response
    {
        return $this->post(
            $this->getBaseUrl() . '/api/v1/ip-addresses',
            $this->getHeaders(),
            $this->formatRequestBody($data),
            $this->getToken()
        );
    }

    /**
     * Delete IP address
     *
     * @param int $id ID of the IP address to delete
     * @param array $data Data to send with the request
     */
    public function destroy(int $id, array $data = []): Response
    {
        return $this->delete(
            url: $this->getBaseUrl() . "/api/v1/ip-addresses/$id",
            headers: $this->getHeaders(),
            data: $data,
            token: $this->getToken()
        );
    }

    /**
     * List IP addresses with optional filters
     *
     * @param array $query Filters to apply on the IP addresses
     */
    public function list(array $query = []): Response
    {
        return $this->get(
            $this->getBaseUrl() . '/api/v1/ip-addresses',
            $this->getHeaders(),
            $query,
            $this->getToken()
        );
    }

    /**
     * Update an existing IP address
     *
     * @param array $query Filters to apply on the IP addresses
     */
    public function update(int $id, array $data = []): Response
    {
        return $this->put(
            $this->getBaseUrl() . "/api/v1/ip-addresses/$id",
            $this->getHeaders(),
            $this->formatRequestBody($data),
            $this->getToken()
        );
    }

    /**
     * Get the base URL for the IP management service
     */
    public function getBaseUrl(): string
    {
        return env('IP_MANAGEMENT_SERVICE_URL');
    }
}
