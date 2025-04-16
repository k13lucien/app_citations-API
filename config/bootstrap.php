<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

if (!isset($_ENV['DB_DRIVER'])) {
    throw new RuntimeException('DB_DRIVER non défini dans le .env');
}

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../app/entities'],
    isDevMode: true
);

$connectionParams = [
    'driver'   => $_ENV['DB_DRIVER'],
    'host'     => $_ENV['DB_HOST'],
    'port'     => $_ENV['DB_PORT'],
    'dbname'   => $_ENV['DB_NAME'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'key'
];

$connection = DriverManager::getConnection($connectionParams, $config);
$entityManager = new EntityManager($connection, $config);

return $entityManager;