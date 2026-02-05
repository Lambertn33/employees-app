<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

use App\Services\AuthServices;

class AuthController extends Controller
{
    public function __construct(private AuthServices $authServices)
    {
    }

    #[OA\Post(
        path: '/api/auth/register',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name','email','password','password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Lambert'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'lambert@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password'),
                    new OA\Property(property: 'password_confirmation', type: 'string', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Registered'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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


    #[OA\Post(
        path: '/api/auth/login',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email','password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: '1|xxxxxxxx'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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


    #[OA\Post(
        path: '/api/auth/logout',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logged out'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function logout(Request $request)
    {
        try {
            return $this->authServices->logout($request->user());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    #[OA\Post(
        path: '/api/auth/forgot-password',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Reset link sent (or silently accepted)'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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


    #[OA\Post(
        path: '/api/auth/reset-password',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email','token','password','password_confirmation'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'token', type: 'string', example: 'reset-token-here'),
                    new OA\Property(property: 'password', type: 'string', example: 'new-password'),
                    new OA\Property(property: 'password_confirmation', type: 'string', example: 'new-password'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Password reset successful'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $data = $request->validated();

            $this->authServices->resetPasswordWithCode(
                email: $data['email'],
                code: $data['code'],
                newPassword: $data['password'],
            );

            return response()->json([
                'message' => 'Password has been reset successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
