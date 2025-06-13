<?php

namespace App\Services;

use App\Webservices\IpAddressWebservice;
use Illuminate\Http\JsonResponse;

class IpAddressService extends BaseService
{
    /**
     * Webservice instance for authentication
     * @var
     */
    protected $webservice;

    public function __construct(IpAddressWebservice $webservice)
    {
        $this->webservice = $webservice;
    }

    /**
     * Create a new IP address
     *
     * @param array $data Data to create a new IP address
     */
    public function create(array $data): JsonResponse
    {
        $response = $this->webservice->create($data);

        return self::responseSuccess($response->json(), $response->status());
    }

    /**
     * Delete an IP address by its ID
     *
     * @param int $id ID of the IP address to delete
     */
    public function destroy(int $id): JsonResponse
    {
        $response = $this->webservice->destroy($id);

        return self::responseSuccess($response->json(), $response->status());
    }

    /**
     * List IP addresses with optional filters
     *
     * @param array $query Filters to apply on the IP addresses
     */
    public function list(array $query): JsonResponse
    {
        $response = $this->webservice->list($query);

        return self::responseSuccess($response->json(), $response->status());
    }

    /**
     * Update an existing IP address
     *
     * @param int $id ID of the IP address to update
     * @param array $data Data to update the IP address
     */
    public function update(int $id, array $data): JsonResponse
    {
        $response = $this->webservice->update($id, $data);

        return self::responseSuccess($response->json(), $response->status());
    }
}
