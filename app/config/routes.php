<?php
$router = new \Phalcon\Mvc\Router();
	
$router->add(
	"/table/:params",
	array(
		"controller" => "table",
		"action"     => 'index',
		"params"     => 1
	)
);
$router->add(
	"/table/:action/add/",
	array(
		"controller" => "table",
		"action"     => "add",
		"params"     => 1
	)
);
$router->add(
	"/table/:action/edit/:int",
	array(
		"controller" => "table",
		"action"     => "edit",
		"tableName"     => 1,
		"elementId"     => 2,
	)
);
$router->add(
	"/table/save",
	array(
		"controller" => "table",
		"action"     => "save"
	)
);
$router->add(
	"/table/delete/:action/:action/:int",
	array(
		"controller" => "table",
		"action"     => "delete",
		"tableName"  => 1,
		"primaryKey" => 2,
		"elementId"  => 3
	)
);
$router->add(
	"/table/getFileUploadForm",
	array(
		"controller" => "table",
		"action"     => "getFileUploadForm"
	)
);
$router->add(
	"/table/getNodeAddForm",
	array(
		"controller" => "table",
		"action"     => "getNodeAddForm"
	)
);
$router->add(
	"/table/autoComplete",
	array(
		"controller" => "table",
		"action"     => "autoComplete"
	)
);
$router->add(
	"/table/uploadFiles/:action/:action",
	array(
		"controller" => "table",
		"action"     => "uploadFiles",
		"tableName"  => 1,
		"fieldName"  => 2
	)
);
$router->add(
	"/ext/:params",
	array(
		"controller" => "ext",
		"action"     => 'index',
		"params"     => 1
	)
);
$router->add(
	"/fld/:params",
	array(
		"controller" => "fld",
		"action"     => 'index',
		"params"     => 1
	)
);
$router->handle();
return $router;
