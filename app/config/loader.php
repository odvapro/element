<?php

$loader = new \Phalcon\Autoload\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */

$loader->setDirectories([
	$config->application->controllersDir,
	$config->application->modelsDir,
	$config->application->libraryDir,
	$config->application->fldDir,
	$config->application->hooksDir,
])->register();

$loader->setNamespaces([
	'Element\Hooks' => $config->application->hooksDir,
]);
