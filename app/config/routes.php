<?php
$router = new \Phalcon\Mvc\Router();

// echo "<pre>".htmlentities(print_r(get_class_methods($router), true))."</pre>";exit();
// $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI );

$router->add(
	"/notfound/", [
		"controller" => 'index',
		"action"     => 'notfound'
	]
);
$router->add(
	"/extensions/:action/",
	array(
		"controller" => "ext",
		"action"     => 1,
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
	"/field/:params",
	array(
		"controller" => "field",
		"action"     => 'index',
		"params"     => 1
	)
);

$router->handle($_SERVER['REQUEST_URI']);
return $router;

?>
