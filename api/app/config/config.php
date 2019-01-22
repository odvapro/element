<?php

return new \Phalcon\Config(array(
	'database' => array(
		'adapter'     => 'Mysql',
		'host'        => 'localhost',
		'username'    => 'root',
		'password'    => 'Hi8R28XY|P',
		'dbname'      => 'tsnv',
	),
	'application' => array(
		'appDir'         => ROOT . '/app/',
		'controllersDir' => ROOT . '/app/controllers/',
		'modelsDir'      => ROOT . '/app/models/',
		'viewsDir'       => ROOT . '/app/views/',
		'pluginsDir'     => ROOT . '/app/plugins/',
		'libraryDir'     => ROOT . '/app/library/',
		'cacheDir'       => ROOT . '/app/cache/',
		'configDir'      => ROOT . '/app/config/',
		'extDir'         => ROOT . '/app/extensions/',
		'fldDir'         => ROOT . '/app/fields/',
		'tViewsDir'      => ROOT . '/app/tviews/',
		'baseUri'        => '/element',
		// указывается относительно baseUri, так как вставляется после него
		'defaultFilesUploadPath' => 'public/upload/'
	)
));
