<?php

namespace Element\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Element\Validation\RequestValidator;
use Element\Request\InstallRequest;

class IndexController
{
	public function install(Request $request)
	{
		$validator = new RequestValidator();
		$validated = $validator->validate($request, InstallRequest::class);

		return new JsonResponse([
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob']
        ]);
	}
}