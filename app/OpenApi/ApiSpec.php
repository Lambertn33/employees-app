<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Employees Attendance API',
    version: '1.0.0',
    description: 'API for authentication, employees CRUD, attendance tracking, and daily PDF/Excel reports.'
)]
#[OA\Server(url: 'http://localhost:8000')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Use: Authorization: Bearer {token}'
)]
final class ApiSpec {}
