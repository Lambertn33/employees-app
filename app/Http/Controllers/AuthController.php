<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\Request;



use App\Services\AuthServices;

class AuthController extends Controller
{
    public function __construct(private AuthServices $authServices)
    {
    }

    public function register(RegisterRequest $request)
    {
        try {
            [$user, $token] = $this->authServices->register($request->validated());
            return response()->json([
                'message' => 'Register successfully.',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            return $this->authServices->logout($request->user());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $this->authServices->sendPasswordResetCode($request->validated()['email']);

            return response()->json([
            'message' => 'If the email exists, a reset code has been sent.',
        ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function resetPassword()
    {

    }
}
