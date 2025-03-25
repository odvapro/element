<?php

use Element\Repositories\BaseRepository;
use Element\Validation\RequestValidator;
use Element\Database\Database;
use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Reference;

// echo '<pre>' . htmlentities(print_r($container, true)) . '</pre>';exit();

$dbParams = [
	'dbname'   => $_ENV['DB_NAME'],
	'user'     => $_ENV['DB_USER'],
	'password' => $_ENV['DB_PASSWORD'],
	'host'     => $_ENV['DB_HOST'],
	'driver'   => $_ENV['DB_DRIVER'],
	'port'     => $_ENV['DB_PORT'],
];
$this->container->register(Connection::class, Connection::class)
	->setFactory([Database::class, 'connect'])
	->addArgument($dbParams);

// Init Request validator ::class
$this->container->register(RequestValidator::class,  RequestValidator::class);

// $this->container->register(IndexController::class, IndexController::class);
// $this->container->compile();
// // Init controller ::class

// // Init database connection
// $this->container->add(Database::class, Database::class);


BaseRepository::setConnetion(
	$this->container->get(Connection::class),
);