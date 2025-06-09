<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'supports_credentials' => true,
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
    'allowed_origins' => [
        'http://localhost:5173',
    ],
    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'Accept',
    ],
    'exposed_headers' => [],
    'max_age' => 0,
];