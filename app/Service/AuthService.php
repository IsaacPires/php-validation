<?php

namespace App\Service;

use App\Interfaces\Service\IAuthService;
use Illuminate\Support\Facades\Auth;

class AuthService implements IAuthService
{
    public function login(array $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            if($user->active)
            {
                return [
                    'success' => true,
                    'message' => 'Autenticação realizada com sucesso.',
                    'user' => $user,
                    'token' => $token,
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'As credenciais fornecidas estão incorretas.',
        ];
    }
}