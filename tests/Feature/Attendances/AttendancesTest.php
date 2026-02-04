<?php

namespace Tests\Feature\Attendances;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendancesTest extends TestCase
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

    private function employee(): Employee
    {
        return Employee::create([
            'names' => 'Employee 1',
            'email' => 'employee1@gmail.com',
            'telephone' => '250788484848',
            'code' => 'EMP-121212'
        ]);
    }

    public function test_admin_can_make_attendance_arrive(): void
    {
        $admin = $this->admin();
        $employee = $this->employee();

        $res = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/attendances/arrive', [
                'employee_id' => $employee->id,
            ]);

        $res->assertCreated()
            ->assertJsonPath('data.employee.id', $employee->id);

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $employee->id,
            'left_at' => null,
        ]);
    }

    public function test_user_cannot_make_attendance_arrive(): void
    {
        $user = $this->user();
        $employee = $this->employee();

        $res = $this->actingAs($user, 'sanctum')
            ->postJson('/api/attendances/arrive', [
                'employee_id' => $employee->id,
            ]);

        $res->assertForbidden();
    }
}
