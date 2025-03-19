<?php

namespace Element;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Element\Exceptions\NotFoundException;

class Kernel
{
	private ContainerBuilder $container;

	public function __construct()
	{
		$this->container = new ContainerBuilder();
		
		require __DIR__ . '/../config/services.php';
	}

	public function handle(Request $request): Response
	{
		$routes = include __DIR__ . '/Routing/routes.php';
		$context = new Routing\RequestContext();
		$context->fromRequest($request);
		$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

		try {
			$parameters = $matcher->match($request->getPathInfo());
			$controller = $this->container->get($parameters['_controller']);
			$method = $parameters['_method'] ?? '__invoke';

			return call_user_func([$controller, $method], $request, $parameters);
		} catch (ResourceNotFoundException $e) {
			throw new NotFoundException();
		} catch (\Throwable $e) {
			throw new \Exception('Error: ' . $e->getMessage(), 500);
		}
	}
}
