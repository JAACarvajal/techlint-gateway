<?php

namespace App\Http\Controllers;

use App\Services\IpAddressService;
use Illuminate\Http\{JsonResponse, Request};

class IpAddressController extends Controller
{
    /**
     * Ip addresss service instance
     * @var
     */
    private $service;

    /**
     * Create ip address controller instance
     *
     * @param IpAddressService $service Ip address service instance
     */
    public function __construct(IpAddressService $service)
    {
        $this->service = $service;
    }

    /**
     * Delete an IP address by its ID
     *
     * @param Request $request Request instance
     * @param int $id ID of the IP address to delete
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        return $this->service->destroy($id);
    }

    /**
     * List IP addresses with optional filters
     *
     * @param Request $request Request instance
     */
    public function index(Request $request): JsonResponse
    {
        return $this->service->list($request->all());
    }

    /**
     * Create a new IP address
     *
     * @param Request $request Request instance
     */
    public function store(Request $request): JsonResponse
    {
        return $this->service->create($request->input('data.attributes'));
    }


    /**
     * Update an existing IP address
     *
     * @param Request $request Request instance
     */
    public function update(Request $request, $id): JsonResponse
    {
        return $this->service->update($id, $request->input('data.attributes'));
    }
}
