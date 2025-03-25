<?php

namespace Element\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Element\Validation\RequestValidator;
use Element\Request\InstallRequest;

use Element\Repositories\EmUserRepository;

class IndexController
{
	public function install(Request $request)
	{
		$validator = new RequestValidator();
		$validated = $validator->validate($request, InstallRequest::class);

		$repo = new EmUserRepository();
		echo '<pre>' . htmlentities(print_r($repo->first(), true)) . '</pre>';exit();
		$repo->first();
		return new JsonResponse([
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob']
        ]);
	}
}