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
        try {
            $response = $this->webservice->check();

            return self::responseSuccess($response->json(), $response->status());
        } catch (\Exception $e) {
            return self::handleException($e);
        }
    }

    /**
     * User login
     *
     * @param array $data Request data for user login
     */
    public function login(array $data): JsonResponse
    {
        try {
            $response = $this->webservice->login($data);

            return self::responseSuccess($response->json(), $response->status());
        } catch (\Exception $e) {
            return self::handleException($e);
        }
    }

    /**
     * Logout user
     *
     * @param string $token JWT token
     */
    public function logout(): JsonResponse
    {
        try {
            $response = $this->webservice->logout();

            return self::responseSuccess($response->json(), $response->status());
        } catch (\Exception $e) {
            return self::handleException($e);
        }
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
