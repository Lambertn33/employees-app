<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;



use App\Services\AuthServices;

class AuthController extends Controller
{
    public function __construct(private AuthServices $authServices)
    {
    }

    public function register(RegisterRequest $request)
    {
        [$user, $token] = $this->authServices->register($request->validated());

        return response()->json([
            'message' => 'Registered successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        [$user, $token] = $this->authServices->login($request->validated());
        return response()->json([
            'message' => 'Login successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 200);
    }

    public function logout()
    {

    }

    public function forgotPassword()
    {

    }

    public function resetPassword()
    {

    }
}
