<?php

namespace Element\Exceptions;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

use Element\Exceptions\NotFoundException;

class Handler
{
	public function handle(Throwable $exception): JsonResponse
	{
		list($message, $code) = match(get_class($exception))
		{
			NotFoundException::class => [
				['other' => 'resource not found'],
				404,
			],
			default => [
				['other' => $exception->getMessage()],
				400,
			]
		};

		return (new JsonResponse(
			[
				'errors' => $message,
			],
			$code
		))->send();
	}
}
