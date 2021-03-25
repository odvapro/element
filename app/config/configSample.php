<?php

return new \Phalcon\Config(array(
	'database' => array(
		'adapter'     => '#adapter#',
		'host'        => '#host#',
		'username'    => '#username#',
		'password'    => '#password#',
		'dbname'      => '#dbname#'
	),
	'application' => array(
		'appDir'         => ROOT . '/app/',
		'controllersDir' => ROOT . '/app/controllers/',
		'modelsDir'      => ROOT . '/app/models/',
		'viewsDir'       => ROOT . '/app/views/',
		'libraryDir'     => ROOT . '/app/library/',
		'cacheDir'       => ROOT . '/app/cache/',
		'configDir'      => ROOT . '/app/config/',
		'extDir'         => ROOT . '/app/extensions/',
		'fldDir'         => ROOT . '/app/fields/',
		'tViewsDir'      => ROOT . '/app/tviews/',
		'hooksDir'       => ROOT . '/app/hooks/',
		'baseUri'        => '#baseuri#',
		// указывается относительно baseUri, так как вставляется после него
		'defaultFilesUploadPath' => '/public/upload'
	)
));
