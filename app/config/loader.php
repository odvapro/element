<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs([
	$config->application->controllersDir,
	$config->application->modelsDir,
	$config->application->libraryDir,
	$config->application->fldDir,
	$config->application->hooksDir,
])->register();

$loader->registerNamespaces([
	'Element\Hooks' => $config->application->hooksDir,
]);
