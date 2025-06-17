<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Auth service instance
     * @var
     */
    private $service;

    /**
     * Create auth controller instance
     *
     * @param AuthService $service Auth service instance
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Check if user is authenticated
     */
    public function check(Request $request)
    {
        return $this->service->check();
    }

    /**
     * User login
     *
     * @param Request $request Request instance
     */
    public function login(Request $request)
    {
        return $this->service->login($request->input('data.attributes'));
    }

    public function logout(Request $request)
    {
        return $this->service->logout();
    }

    /**
     * Refresh JWT token
     */
    public function refresh()
    {
        return $this->service->refresh();
    }
}
