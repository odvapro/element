<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Assets\Manager  as AssetsManager;

/**
 * CONSTATNTS
 */
define('ROOT', rtrim($_SERVER['DOCUMENT_ROOT'],'/'));

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Register events manager 
 */
$di->set('dispatcher', function() use ($di)
{
	$eventsManager = $di->getShared('eventsManager');

	$security = new Security($di);

	/**
	 * Listen for events in the dispatcher using the Security plugin
	 */
	$eventsManager->attach('dispatch', $security);

	$dispatcher = new Phalcon\Mvc\Dispatcher();
	$dispatcher->setEventsManager($eventsManager);

	return $dispatcher;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
	$url = new UrlResolver();
	$url->setBaseUri($config->application->baseUri);

	return $url;
}, true);

$di->set('router', function(){
	return require __DIR__ . '/routes.php';
}, true);

$di->set('tableEditor', function()
{
	return new TableEditor();
}, true);

$di->set('fields', function() use ($di)
{
	return new Fields($di);
}, true);

$di->set('tableviews', function() use ($di)
{
	return new TableViews($di);
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

	$view = new View();

	$view->setViewsDir($config->application->viewsDir);

	$view->registerEngines(array(
		'.volt' => function ($view, $di) use ($config) {

			$volt = new VoltEngine($view, $di);

			$volt->setOptions(array(
				'compiledPath' => $config->application->cacheDir,
				'compiledSeparator' => '_',
				'compileAlways' => true
			));

			return $volt;
		},
		'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
	));

	return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
	return new DbAdapter(array(
		'host' => $config->database->host,
		'username' => $config->database->username,
		'password' => $config->database->password,
		'dbname' => $config->database->dbname,
		"options" => array(
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
			PDO::ATTR_CASE => PDO::CASE_LOWER,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
		)
	));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
	return new MetaDataAdapter();
});

$di->set('assets', function () {
	return new AssetsManager();
});

$di->set('config', function () use ($config){
	return $config;
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
	$session = new SessionAdapter();
	$session->start();

	return $session;
});
