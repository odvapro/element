<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('api', new Route('/api/v1/install', [
	'_controller' => 'Element\\Controllers\\IndexController',
	'_method' => 'install'
]));

return $routes;
