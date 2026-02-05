<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Attendance',
    type: 'object',
    required: ['id', 'employee_id', 'arrived_at', 'left_at', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 10),
        new OA\Property(property: 'employee_id', type: 'integer', example: 5),
        new OA\Property(property: 'arrived_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-05T08:10:00+02:00'),
        new OA\Property(property: 'left_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-05T17:20:00+02:00'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-05T08:10:00+02:00'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-05T17:20:00+02:00'),
    ]
)]
#[OA\Schema(
    schema: 'AttendanceWithEmployee',
    type: 'object',
    required: ['id', 'employee_id', 'arrived_at', 'left_at', 'employee'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 10),
        new OA\Property(property: 'employee_id', type: 'integer', example: 5),
        new OA\Property(property: 'arrived_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-05T08:10:00+02:00'),
        new OA\Property(property: 'left_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-05T17:20:00+02:00'),
        new OA\Property(ref: '#/components/schemas/Employee', property: 'employee', type: 'object'),
    ]
)]
#[OA\Schema(
    schema: 'AttendanceArriveRequest',
    type: 'object',
    required: ['employee_id'],
    properties: [
        new OA\Property(property: 'employee_id', type: 'integer', example: 5),
    ]
)]
#[OA\Schema(
    schema: 'AttendanceLeaveRequest',
    type: 'object',
    required: ['employee_id'],
    properties: [
        new OA\Property(property: 'employee_id', type: 'integer', example: 5),
    ]
)]
#[OA\Schema(
    schema: 'PaginatedAttendances',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/AttendanceWithEmployee')
        ),
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(property: 'per_page', type: 'integer', example: 10),
        new OA\Property(property: 'total', type: 'integer', example: 25),
        new OA\Property(property: 'last_page', type: 'integer', example: 3),
    ]
)]
#[OA\Schema(
    schema: 'ErrorMessage',
    type: 'object',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Employee already has an open attendance.'),
    ]
)]
final class AttendanceSchemas
{
    // Schema container
}
