<?php

namespace App\Services;

use App\Webservices\UserWebservice;
use Illuminate\Http\JsonResponse;

class UserService extends BaseService
{
    /**
     * Webservice instance for user
     * @var
     */
    protected $webservice;

    public function __construct(UserWebservice $webservice)
    {
        $this->webservice = $webservice;
    }

    /**
     * Create a new user
     *
     * @param array $data Request data for user creation
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
}
