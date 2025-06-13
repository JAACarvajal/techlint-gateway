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
        try {
            $response = $this->webservice->create($data);

            return self::responseSuccess($response->json(), $response->status());
        } catch (\Exception $e) {
            return self::handleException($e);
        }
    }

    /**
     * Delete an IP address by its ID
     *
     * @param int $ipId ID of the IP address to delete
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $response = $this->webservice->destroy($id);

            return self::responseSuccess($response->json(), $response->status());
        } catch (\Exception $e) {
            return self::handleException($e);
        }
    }

    /**
     * List IP addresses with optional filters
     *
     * @param array $query Filters to apply on the IP addresses
     */
    public function list(array $query): JsonResponse
    {
        try {
            $response = $this->webservice->list($query);

            return self::responseSuccess($response->json(), $response->status());
        } catch (\Exception $e) {
            return self::handleException($e);
        }
    }

    /**
     * Update an existing IP address
     *
     * @param int $id ID of the IP address to update
     * @param array $data Data to update the IP address
     */
    public function update(int $id, array $data): JsonResponse
    {
        try {
            $response = $this->webservice->update($id, $data);

            return self::responseSuccess($response->json(), $response->status());
        } catch (\Exception $e) {
            return self::handleException($e);
        }
    }
}
