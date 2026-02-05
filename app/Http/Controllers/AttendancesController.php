<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Services\AttendanceServices;
use App\Http\Requests\Attendances\ArriveAndLeaveRequest;
use App\Http\Requests\Attendances\AttendancesListRequest;
use App\Http\Resources\AttendanceResource;
use OpenApi\Attributes as OA;

class AttendancesController extends Controller
{
    public function __construct(private AttendanceServices $attendanceServices) {}


    #[OA\Get(
        path: '/api/attendances',
        operationId: 'attendancesIndex',
        tags: ['Attendances'],
        security: [['sanctum' => []]],
        summary: 'List attendances (with optional filters)',
        parameters: [
            new OA\QueryParameter(
                name: 'employee_id',
                required: false,
                description: 'Filter by employee id',
                schema: new OA\Schema(type: 'integer', example: 5)
            ),
            new OA\QueryParameter(
                name: 'from',
                required: false,
                description: 'Start date (YYYY-MM-DD)',
                schema: new OA\Schema(type: 'string', example: '2026-02-01')
            ),
            new OA\QueryParameter(
                name: 'to',
                required: false,
                description: 'End date (YYYY-MM-DD)',
                schema: new OA\Schema(type: 'string', example: '2026-02-05')
            ),
            new OA\QueryParameter(
                name: 'per_page',
                required: false,
                description: 'Items per page (1..100), default 10',
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
            new OA\QueryParameter(
                name: 'page',
                required: false,
                description: 'Page number',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated attendances',
                content: new OA\JsonContent(ref: '#/components/schemas/PaginatedAttendances')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(AttendancesListRequest $request) 
    {
        $attendances = $this->attendanceServices->getAttendances($request);
        return AttendanceResource::collection($attendances);

    }

    #[OA\Post(
        path: '/api/attendances/arrive',
        operationId: 'attendancesArrive',
        tags: ['Attendances'],
        security: [['sanctum' => []]],
        summary: 'Mark employee as arrived',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/AttendanceArriveRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Attendance created',
                content: new OA\JsonContent(ref: '#/components/schemas/Attendance')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 409,
                description: 'Conflict (already arrived / open attendance)',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorMessage')
            ),
        ]
    )]
    public function arrive(ArriveAndLeaveRequest $request)
    {
        $attendance = $this->attendanceServices->arrive($request->validated()['employee_id']);
        return (new AttendanceResource($attendance->load('employee')))
            ->response()
            ->setStatusCode(201);
    }


    #[OA\Post(
        path: '/api/attendances/leave',
        operationId: 'attendancesLeave',
        tags: ['Attendances'],
        security: [['sanctum' => []]],
        summary: 'Mark employee as left',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/AttendanceLeaveRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Attendance updated (left_at set)',
                content: new OA\JsonContent(ref: '#/components/schemas/Attendance')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 409,
                description: 'Conflict (no open attendance to close)',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorMessage')
            ),
        ]
    )]
    public function leave(ArriveAndLeaveRequest $request)
    {
        $attendance = $this->attendanceServices->leave($request->validated()['employee_id']);
        return (new AttendanceResource($attendance->load('employee')))
            ->response()
            ->setStatusCode(200);
    }
}
