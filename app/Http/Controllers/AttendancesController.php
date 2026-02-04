<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Services\AttendanceServices;
use App\Http\Requests\Attendances\ArriveAndLeaveRequest;
use App\Http\Requests\Attendances\AttendancesListRequest;
use App\Http\Resources\AttendanceResource;

class AttendancesController extends Controller
{
    public function __construct(private AttendanceServices $attendanceServices) {}

    public function index(AttendancesListRequest $request) 
    {
        $attendances = $this->attendanceServices->getAttendances($request);
        return AttendanceResource::collection($attendances);

    }

    public function arrive(ArriveAndLeaveRequest $request)
    {
        $attendance = $this->attendanceServices->arrive($request->validated()['employee_id']);
        return (new AttendanceResource($attendance->load('employee')))
            ->response()
            ->setStatusCode(201);
    }

    public function leave(ArriveAndLeaveRequest $request)
    {
        $attendance = $this->attendanceServices->leave($request->validated()['employee_id']);
        return (new AttendanceResource($attendance->load('employee')))
            ->response()
            ->setStatusCode(200);
    }
}
