<?php


declare(strict_types=1);


return [
    
    'dependencies' => [
        'aliases' => [
            
            // Tell mezzio-authentication to use the PhpSession
            // adapter:
            \Mezzio\Authentication\AuthenticationInterface::class => \Mezzio\Authentication\Session\PhpSession::class,
            
            // Use the default PdoDatabase user repository. This assumes
            // you have configured that service correctly.
            \Mezzio\Authentication\UserRepositoryInterface::class => \Mezzio\Authentication\UserRepository\PdoDatabase::class,
        ]
    ],
    'authentication' => [
        'pdo' => [
            'table' => 'recrutador',
            'field' => [
                'identity' => 'email',
                'password' => 'password',
            ],
            'sql_get_roles'     => 'SQL to retrieve roles with :identity parameter',
            'sql_get_details'   => 'select id from recrutador where email = :identity',
        ],
        'redirect' => '/api/without-session'
    ],
];