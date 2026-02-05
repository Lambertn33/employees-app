<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ResetPasswordRequest',
    required: ['token', 'email', 'password', 'password_confirmation'],
    properties: [
        new OA\Property(property: 'token', type: 'string', example: 'reset-token-from-email'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'lambert@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'NewStrongPass123!'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'NewStrongPass123!'),
    ],
    type: 'object'
)]
final class ResetPasswordRequestSchema {}