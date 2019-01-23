<?php

error_reporting(E_ALL);
/**
 * CONSTATNTS
 */
define('ROOT', rtrim($_SERVER['DOCUMENT_ROOT'],'/'));

if(!file_exists(__DIR__ . "/../app/config/config.php"))
{
	header('location: /config-setup.php');
	exit();
}

/**
 * Read the configuration
 */
$config = include __DIR__ . "/../app/config/config.php";

/**
 * Read auto-loader
 */
include __DIR__ . "/../app/config/loader.php";

/**
 * Read services
 */
include __DIR__ . "/../app/config/services.php";

/**
 * Handle the request
 */
$application = new \Phalcon\Mvc\Application($di);

echo $application->handle()->getContent();
