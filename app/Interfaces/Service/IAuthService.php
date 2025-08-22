<?php

namespace App\Interfaces\Service;

interface IAuthService
{
    public function login(array $credentials): array;
}