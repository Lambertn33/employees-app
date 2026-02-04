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

    public function test_admin_can_make_attendance_arrival(): void
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

    public function test_user_cannot_make_attendance_arrival(): void
    {
        $user = $this->user();
        $employee = $this->employee();

        $res = $this->actingAs($user, 'sanctum')
            ->postJson('/api/attendances/arrive', [
                'employee_id' => $employee->id,
            ]);

        $res->assertForbidden();
    }

    public function test_employee_cannot_arrive_twice_without_leaving(): void
    {
        $admin = $this->admin();
        $employee = $this->employee();

        Attendance::create([
            'employee_id' => $employee->id,
            'arrived_at' => now()->subMinutes(10)
        ]);

        $res = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/attendances/arrive', [
                'employee_id' => $employee->id,
            ]);

        $res->assertStatus(422)
            ->assertJsonValidationErrors(['employee_id']);
    }

    public function test_admin_can_leave_open_attendance(): void
    {
        $admin = $this->admin();
        $employee = $this->employee();

        $openAttendance = Attendance::create([
            'employee_id' => $employee->id,
            'arrived_at' => now()->subMinutes(10)
        ]);

        $res = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/attendances/leave', [
                'employee_id' => $employee->id,
            ]);

        $res->assertOk()
            ->assertJsonPath('data.id', $openAttendance->id);

        $this->assertDatabaseHas('attendances', [
            'id' => $openAttendance->id,
        ]);

        $this->assertNotNull($openAttendance->fresh()->left_at);
    }

    public function test_user_can_leave_open_attendance(): void
    {
        $user = $this->user();
        $employee = $this->employee();

        $openAttendance = Attendance::create([
            'employee_id' => $employee->id,
            'arrived_at' => now()->subMinutes(10)
        ]);

        $res = $this->actingAs($user, 'sanctum')
            ->postJson('/api/attendances/leave', [
                'employee_id' => $employee->id,
            ]);

        $res->assertForbidden();
    }
}
