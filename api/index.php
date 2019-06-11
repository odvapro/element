<?php
error_reporting(E_ALL);
/**
 * CONSTATNTS
 */

define('ROOT', realpath(__DIR__ . '/..'));

/**
 * URI change relative to base uri
 */
$_SERVER['REQUEST_URI'] = str_replace('/element/api/', '/', $_SERVER['REQUEST_URI']);

try
{
	if(!file_exists(ROOT . "/app/config/config.php"))
	{
		echo json_encode(['success' => false, 'message' => 'Config not found']);
		exit();
	}
	/**
	 * Read the configuration
	 */
	$config = include ROOT . "/app/config/config.php";

	/**
	 * Read auto-loader
	 */
	include ROOT . "/app/config/loader.php";

	/**
	 * Read services
	 */
	include ROOT . "/app/config/services.php";

	/**
	 * Composer
	 */
	if(file_exists(ROOT . '/vendor/autoload.php'))
	{
		require ROOT . '/vendor/autoload.php';
	}

	/**
	 * Handle the request
	 */
	$application = new \Phalcon\Mvc\Application($di);

	echo $application->handle()->getContent();

}
catch(\Exception $e)
{
	echo $e->getMessage();
}
