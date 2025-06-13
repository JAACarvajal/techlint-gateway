<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use App\Webservices\AuthWebservice;

class AuthService extends BaseService
{
    /**
     * Webservice instance for authentication
     * @var
     */
    protected $webservice;

    public function __construct(AuthWebservice $webservice)
    {
        $this->webservice = $webservice;
    }

    /**
     * Check if user is authenticated
     */
    public function check(): JsonResponse
    {
        $response = $this->webservice->check();

        return self::responseSuccess($response->json(), $response->status());
    }

    /**
     * User login
     *
     * @param array $data Request data for user login
     */
    public function login(array $data): JsonResponse
    {
        $response = $this->webservice->login($data);

        return self::responseSuccess($response->json(), $response->status());
    }

    /**
     * Logout user
     */
    public function logout(): JsonResponse
    {
        $response = $this->webservice->logout();

        return self::responseSuccess($response->json(), $response->status());
    }

    /**
     * Refresh JWT token
     */
    public function refresh(): JsonResponse
    {
        try {

        } catch (\Exception $e) {
            return self::handleException($e);
        }
    }
}
