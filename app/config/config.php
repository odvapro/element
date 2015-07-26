<?php

define('ROOT', rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/');

return new \Phalcon\Config(array(
	'database' => array(
		'adapter'     => 'Mysql',
		'host'        => 'localhost',
		'username'    => 'root',
		'password'    => 'pass',
		'dbname'      => 'element_cms',
	),
	'application' => array(
		'controllersDir'         => __DIR__ . '/../../app/controllers/',
		'modelsDir'              => __DIR__ . '/../../app/models/',
		'viewsDir'               => __DIR__ . '/../../app/views/',
		'pluginsDir'             => __DIR__ . '/../../app/plugins/',
		'libraryDir'             => __DIR__ . '/../../app/library/',
		'cacheDir'               => __DIR__ . '/../../app/cache/',
		'configDir'              => __DIR__ . '/../../app/config/',
		'baseUri'                => '/element/',
		
		// указывается относительно baseUri, так как вставляется после него
		'defaultFilesUploadPath' => 'public/upload/'
	)
));
