<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MessageResponse',
    required: ['message'],
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'OK'),
    ],
    type: 'object'
)]
final class MessageResponseSchema {}