<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Lambert',
            'email' => 'lambert@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'role'],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'lambert@example.com',
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_returns_token_for_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'lambert@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'role'],
                'token',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_fails_for_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'lambert@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'Invalid credentials',
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_logout_revokes_current_token(): void
    {
        $user = User::factory()->create();

        $plainToken = $user->createToken('test')->plainTextToken;

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $response = $this->withHeader('Authorization', 'Bearer '.$plainToken)
            ->postJson('/api/auth/logout');

        $response->assertOk()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_forgot_password_returns_generic_message_when_user_does_not_exist(): void
    {
        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'missing@example.com',
        ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'If the email exists, a reset code has been sent.',
            ]);

        $this->assertDatabaseCount('password_reset_tokens', 0);
    }

    public function test_forgot_password_stores_token_for_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'lambert@example.com',
        ]);

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'If the email exists, a reset code has been sent.',
            ]);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);

        $row = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        $this->assertNotNull($row);
        $this->assertNotEmpty($row->token);
        $this->assertNotNull($row->created_at);
    }
}
