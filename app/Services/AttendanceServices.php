<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Validation\ValidationException;
use App\Services\MailServices;
use App\Requests\Attendances\AttendancesListRequest;

class AttendanceServices
{
    public function __construct(
        private MailServices $mailServices,
    ) {}

    public function getAttendances(AttendancesListRequest $request)
    {
        $perPage = (int) ($request->per_page ?? 10);

        $query = Attendance::with('employee');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('arrived_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('arrived_at', '<=', $request->to);
        }

        return $query->latest('arrived_at')->paginate($perPage);
    }

    public function arrive(int $employeeId): Attendance
    {
        $employee = Employee::findOrFail($employeeId);

        $open = Attendance::where('employee_id', $employee->id)
            ->whereNull('left_at')
            ->latest('arrived_at')
            ->first();

        if ($open) {
            throw ValidationException::withMessages([
                'employee_id' => ['Employee already has an open attendance.'],
            ]);
        }

        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'arrived_at' => now(),
        ]);

        $this->mailServices->sendMail(
            to: $employee->email,
            subject: 'Arrival Record',
            body: "Dear $employee->names, your attendance has been recorded"
        );
        return $attendance;
    }

    public function leave(int $employeeId): Attendance
    {
        $employee = Employee::findOrFail($employeeId);

        $open = Attendance::where('employee_id', $employee->id)
            ->whereNull('left_at')
            ->latest('arrived_at')
            ->first();

        if (!$open) {
            throw ValidationException::withMessages([
                'employee_id' => ['Employee has no open attendance to close.'],
            ]);
        }

        $open->update([
            'left_at' => now(),
        ]);

        return $open->refresh();
    }
}