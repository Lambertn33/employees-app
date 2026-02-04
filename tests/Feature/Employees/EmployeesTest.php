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

    public function test_user_cannot_create_employee(): void
    {
        $user = $this->user(); 

        $res = $this->actingAs($user, 'sanctum')
            ->postJson('/api/employees', [
                'names' => 'John Doe',
                'email' => 'john@example.com',
                'telephone' => '250788123456',
            ]);
        $res->assertForbidden();
    }

    public function test_admin_can_update_employee(): void
    {
        $admin = $this->admin();
        $employee = Employee::create([
            'names' => 'Employee 1',
            'email' => 'employee1@gmail.com',
            'telephone' => '250788484848',
            'code' => 'EMP-121212'
        ]);

        $res = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/employees/{$employee->id}", [
                'names' => 'Updated Name',
            ]);

        $res->assertOk()
            ->assertJsonFragment(['names' => 'Updated Name']);
    }
}
