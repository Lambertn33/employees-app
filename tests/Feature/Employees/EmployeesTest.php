<?php

namespace Tests\Feature\Employees;

use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeesTest extends TestCase
{
    use RefreshDatabase;


    private function admin(): User
    {
        return User::factory()->create(['role' => User::ADMIN]);
    }

    private function user(): User
    {
        return User::factory()->create(['role' => User::USER]);
    }

    public function test_admin_can_create_employee(): void
    {
        $admin = $this->admin();

        $payload = [
            'names' => 'John Doe',
            'email' => 'john@example.com',
            'telephone' => '250780000000',
            'code' => 'EMP-110102'
        ];

        $res = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/employees', $payload);

        $res->assertCreated()
            ->assertJsonFragment(['email' => 'john@example.com']);

        $this->assertDatabaseHas('employees', [
            'email' => 'john@example.com',
        ]);
    }
}
