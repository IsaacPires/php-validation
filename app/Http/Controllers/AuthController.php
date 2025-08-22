<?php

namespace App\Http\Controllers;

use App\Http\Requests\Credentials;
use App\Interfaces\Service\IAuthService;
use Illuminate\Support\Arr;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Credentials $credentials)
    {
        $credentials = $credentials->validated();

        $response = $this->authService->login(Arr::only($credentials, ['email', 'password']));

        if ($response['success']) {
            return response()->json([
                'message' => $response['message'],
                'user' => $response['user'],
                'token' => $response['token'],
            ], 200);
        }

        return response()->json([
            'message' => $response['message'],
        ], 401);
    }
}