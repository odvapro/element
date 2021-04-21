<?php

use Phalcon\DI\FactoryDefault;

use Phalcon\Mvc\Dispatcher as PhDispatcher;

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

use Phalcon\Db\Adapter\Pdo\Factory;

use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Logger\Adapter\File as FileAdapter;

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

	$authMiddleware = new AuthMiddleware($di);
	$eventsManager->attach('dispatch', $authMiddleware);

	$eventsManager->attach(
		"dispatch:beforeException",
		/**
		 * перед переходом на странциу проверять есть ли такая странца
		 */
		function($event, $dispatcher, $exception)
		{
			//если нет такой страницы - перекидывает на страницу notfound
			switch ($exception->getCode())
			{
				case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
				case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
					$dispatcher->forward(
						[
							'controller' => 'notfound',
							'action'     => 'index',
						]
					);
					return false;
			}
		}
	);

	$dispatcher = new Phalcon\Mvc\Dispatcher();
	$dispatcher->setEventsManager($eventsManager);

	return $dispatcher;
});

/**
 * Логгер
 */
$di->set('logger', function() use($config)
{
	return new FileAdapter(ROOT . '/app/logs/element.log');
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config)
{
	$view = new View();
	$view->setViewsDir($config->application->viewsDir);
	$view->registerEngines(array(
		/**
		 * Регистрация вольта
		 */
		'.volt' => function ($view, $di) use ($config)
		{
			$volt = new VoltEngine($view, $di);
			$volt->setOptions([
				'compiledPath'      => $config->application->cacheDir,
				'compiledSeparator' => '_',
				'compileAlways'     => true
			]);
			return $volt;
		},
		'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
	));
	return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$options = [
	'host'     => $config->database->host,
	'username' => $config->database->username,
	'password' => $config->database->password,
	'dbname'   => $config->database->dbname,
	'adapter'  => $config->database->adapter,
	"options" => [
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"
	]
];
$db  = Factory::load($options);
$di->set('eldb', function () use ($config,$db)
{
	$dbs = ['Mysql'=>'Sql','Posgres'=>'Sql'];
	$adpterName = "{$dbs[$config->database->adapter]}Adapter";
	$eldb       = new $adpterName($db);
	return $eldb;
});

$di->set('db', $db);
/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config)
{
	$url = new UrlResolver();
	$url->setBaseUri($config->application->baseUri);

	return $url;
}, true);

/**
 * Set router
 */
$di->set('router', function(){
	return require __DIR__ . '/routes.php';
}, true);

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function()
{
	return new MetaDataAdapter();
});

/**
 * Set config
 */
$di->set('config', function() use ($config)
{
	return $config;
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function()
{
	$session = new SessionAdapter();
	$session->start();
	return $session;
});

$di->set('element', function () use ($di)
{
	$element = new Element($di->get('eldb'), $di);
	return $element;
});

$di->set('user', function () use ($di)
{
	static $user = null;

	if (empty($user) && !empty($di->get('session')->get('auth')))
		$user = EmUsers::findFirst($di->get('session')->get('auth'));

	return $user;
});

$di->set('group', function () use ($di)
{
	static $group = null;

	if (empty($group) && !empty($di->get('session')->get('token')))
	{
		$token = EmTokens::findFirstByValue($di->get('session')->get('token'));
		if (!empty($token)) $group = $token->group;
	}

	return $group;
});
