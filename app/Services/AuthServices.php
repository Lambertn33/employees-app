<?php

namespace App\Services;
use App\Models\User;
use App\Services\MailServices;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthServices
{
    public function __construct(
        private MailServices $mailServices,
    ) {}

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

    public function sendPasswordResetCode(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return;
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        $body = "Your password reset code is: {$code}\nThis code expires in 15 minutes.";

        $this->mailServices->sendMail(
            to: $user->email,
            subject: 'Password Reset Code',
            body: $body
        );
    }
}