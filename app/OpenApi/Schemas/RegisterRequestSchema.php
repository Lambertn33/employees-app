<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RegisterRequest',
    required: ['name', 'email', 'password', 'password_confirmation'],
    type: 'object',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Lambert Nsengimana'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'lambert@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'StrongPass123!'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'StrongPass123!'),
    ]
)]
final class RegisterRequestSchema {}
