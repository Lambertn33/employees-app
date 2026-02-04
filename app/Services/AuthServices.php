<?php

namespace App\Services;
use App\Models\User;
use App\Services\MailServices;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

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

    public function resetPasswordWithCode(string $email, string $code, string $newPassword): void
    {
        $row = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$row) {
            throw ValidationException::withMessages([
                'code' => ['Invalid or expired reset code.'],
            ]);
        }

        $createdAt = Carbon::parse($row->created_at);
        if ($createdAt->diffInMinutes(now()) > 15) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            throw ValidationException::withMessages([
                'code' => ['Invalid or expired reset code.'],
            ]);
        }

        if (!Hash::check($code, $row->token)) {
            throw ValidationException::withMessages([
                'code' => ['Invalid or expired reset code.'],
            ]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'code' => ['Invalid or expired reset code.'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($newPassword),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        $user->tokens()->delete();
    }
}