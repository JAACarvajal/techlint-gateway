<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\{JsonResponse, Request};

class UserController extends Controller
{
    /**
     * Ip addresss service instance
     * @var
     */
    private $service;

    /**
     * Create user address controller instance
     *
     * @param UserService $service user address service instance
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Create a new user
     *
     * @param Request $request Request instance
     */
    public function store(Request $request): JsonResponse
    {
        return $this->service->create($request->input('data.attributes'));
    }
}
