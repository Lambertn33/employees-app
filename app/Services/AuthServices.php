<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthServices
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::USER,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return [$user, $token];
    }

    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \RuntimeException('Invalid credentials.');
        }

        $token = $user->createToken('api')->plainTextToken;
        return [$user, $token];
    }

    public function logout(User $user)
    {
        $user->tokens()->delete();
        return ['success' => true, 'message' => 'Logged out successfully'];
    }
}