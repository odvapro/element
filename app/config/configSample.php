<?php

return new \Phalcon\Config(array(
	'database' => array(
		'adapter'     => 'Mysql',
		'host'        => '#host#',
		'username'    => '#username#',
		'password'    => '#password#',
		'dbname'      => '#dbname#',
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
		'tViewsDir'      => __DIR__ . '/../../app/tviews/',
		'baseUri'        => '#baseuri#',
		// указывается относительно baseUri, так как вставляется после него
		'defaultFilesUploadPath' => 'public/upload/'
	)
));
