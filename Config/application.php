<?php

return [
    'database' => [
        'host' => getenv('DATABASE_HOST'),
        'username' => getenv('DATABASE_USERNAME'),
        'password' => getenv('DATABASE_PASSWORD'),
        'database' => getenv('DATABASE_NAME'),
    ],
    'security' => [
        'salt' => getenv('SECURITY_SALT'),
        'pepper' => getenv('SECURITY_PEPPER')
    ],
    'email' => [
        'server' => getenv('EMAIL_SERVER'),
        'port' => getenv('EMAIL_PORT'),
        'username' => getenv('EMAIL_USERNAME'),
        'password' => getenv('EMAIL_PASSWORD'),
    ],
    'github' => [
        'username' => getenv('GIT_USERNAME'),
    ],
    'BasePath' => getenv('BASE_PATH'),
];
