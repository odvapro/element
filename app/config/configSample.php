<?php

return new \Phalcon\Config(array(
	'database' => array(
		'adapter'     => 'Mysql',
		'host'        => 'localhost',
		'username'    => 'root',
		'password'    => 'pass',
		'dbname'      => 'element_cms',
	),
	'application' => array(
		'appDir'         => __DIR__ . '/../../app/',
		'controllersDir' => __DIR__ . '/../../app/controllers/',
		'modelsDir'      => __DIR__ . '/../../app/models/',
		'viewsDir'       => __DIR__ . '/../../app/views/',
		'pluginsDir'     => __DIR__ . '/../../app/plugins/',
		'libraryDir'     => __DIR__ . '/../../app/library/',
		'cacheDir'       => __DIR__ . '/../../app/cache/',
		'configDir'      => __DIR__ . '/../../app/config/',
		'extDir'         => __DIR__ . '/../../app/extensions/',
		'fldDir'         => __DIR__ . '/../../app/fields/',
		'baseUri'        => '/element/', 
		
		// указывается относительно baseUri, так как вставляется после него
		'defaultFilesUploadPath' => 'public/upload/'
	)
));
