<?php

declare(strict_types=1);

return [

    'dependencies' => [
        'factories'  => [
            //setando a factory disponibilizada pelo package ContainerInteropDoctrine
            // como factory para o doctrine. Isto eh feito para o mesmo funcionar Mezzio
            \Doctrine\ORM\EntityManagerInterface::class => \Roave\PsrContainerDoctrine\EntityManagerFactory::class,
        ],
    ],

    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'auto_generate_proxy_classes' => false
            ],
        ],
        'connection' => [
            'orm_default' => [
                'driver_class' => \Doctrine\DBAL\Driver\PDOPgSql\Driver::class,
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
            ],
        ],
        'migrations' => [
            'name'=> 'Developers Search Migrations',
            'migrations_namespace' => 'Migrations',
            'migrations_directory' => __DIR__ . '/../../data/migrations',
            'table_name' => 'doctrine_migration_versions',
            'column_name' => 'version',
            'column_length' => 14,
            'executed_at_column_name' => 'executed_at',
            'all_or_nothing' => true,
            'check_database_platform' => true,
        ],
    ]

];

