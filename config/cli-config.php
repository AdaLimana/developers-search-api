<?php

declare(strict_types = 1);

$container = require __DIR__ . '/container.php';

$entityManager = $container->get(\Doctrine\ORM\EntityManagerInterface::class);

$migrationsConfiguration = $container->get('config')['doctrine']['migrations'];

$configuration = new \Doctrine\Migrations\Configuration\Configuration($entityManager->getConnection());
$configuration->setMigrationsDirectory($migrationsConfiguration['migrations_directory']);
$configuration->setName($migrationsConfiguration['name']);
$configuration->setMigrationsNamespace($migrationsConfiguration['migrations_namespace']);
$configuration->setMigrationsTableName($migrationsConfiguration['table_name']);
$configuration->setMigrationsColumnName($migrationsConfiguration['column_name']);
$configuration->setMigrationsColumnLength($migrationsConfiguration['column_length']);
$configuration->setMigrationsExecutedAtColumnName($migrationsConfiguration['executed_at_column_name']);
$configuration->setAllOrNothing($migrationsConfiguration['all_or_nothing']);
$configuration->setCheckDatabasePlatform($migrationsConfiguration['check_database_platform']);
$configuration->createMigrationTable();

return new \Symfony\Component\Console\Helper\HelperSet([
    
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager),
    'configuration' => new Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper($entityManager->getConnection(), $configuration)
    
]);