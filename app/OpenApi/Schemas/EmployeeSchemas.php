<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Employee',
    type: 'object',
    required: ['id', 'names', 'email', 'code', 'telephone', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'names', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
        new OA\Property(property: 'code', type: 'string', example: 'EMP-20260205143022'),
        new OA\Property(property: 'telephone', type: 'string', example: '250788484841'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-05T14:30:22+02:00'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-05T14:30:22+02:00'),
    ]
)]
#[OA\Schema(
    schema: 'EmployeeCreateRequest',
    type: 'object',
    required: ['names', 'email', 'telephone'],
    properties: [
        new OA\Property(property: 'names', type: 'string', maxLength: 255, example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255, example: 'john@example.com'),
        new OA\Property(property: 'telephone', type: 'string', example: '250788484841', description: 'Must match /^2507\\d{8}$/'),
    ]
)]
#[OA\Schema(
    schema: 'EmployeeUpdateRequest',
    type: 'object',
    properties: [
        new OA\Property(property: 'names', type: 'string', maxLength: 255, example: 'John Doe Updated'),
        new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255, example: 'john.updated@example.com'),
        new OA\Property(property: 'telephone', type: 'string', example: '250788484842', description: 'Must match /^2507\\d{8}$/'),
        // include 'code' only if your update allows it
        new OA\Property(property: 'code', type: 'string', example: 'EMP-20260205143022'),
    ]
)]
#[OA\Schema(
    schema: 'ValidationError',
    type: 'object',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            additionalProperties: new OA\AdditionalProperties(
                type: 'array',
                items: new OA\Items(type: 'string')
            ),
            example: [
                'email' => ['The email has already been taken.'],
                'telephone' => ['Telephone must be a 12-digit number starting with 2507.'],
            ]
        ),
    ]
)]
#[OA\Schema(
    schema: 'PaginatedEmployees',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Employee')
        ),
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(property: 'per_page', type: 'integer', example: 10),
        new OA\Property(property: 'total', type: 'integer', example: 25),
        new OA\Property(property: 'last_page', type: 'integer', example: 3),
    ]
)]
final class EmployeeSchemas
{
    // Schema container
}
