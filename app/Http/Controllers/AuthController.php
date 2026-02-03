<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;

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

    public function login()
    {

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
