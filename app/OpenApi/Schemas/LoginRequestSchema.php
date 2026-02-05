<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LoginRequest',
    required: ['email', 'password'],
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'lambert@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'StrongPass123!'),
    ],
    type: 'object'
)]
final class LoginRequestSchema {}