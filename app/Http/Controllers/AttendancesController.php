<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Services\AttendanceServices;
use App\Http\Requests\Attendances\ArriveAndLeaveRequest;

class AttendancesController extends Controller
{
    public function __construct(private AttendanceServices $attendanceServices) {}

    public function arrive(ArriveAndLeaveRequest $request)
    {
        try {
            $attendance = $this->attendanceServices->arrive($request->validated()['employee_id']);

            return response()->json($attendance, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
