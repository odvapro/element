<?php

namespace Element\Validation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Element\Excaptions\ValidationException;

class RequestValidator
{
	public function validate(Request $request, string $dtoClass): ValidationInterface
	{
		if (!class_exists($dtoClass)) {
			throw new \InvalidArgumentException("Request class {$dtoClass} does not exist.");
		}

		$dto = (new $dtoClass)->fromHttp($request);
		$validator = Validation::createValidator();

		$violations = $validator->validate($request->toArray(), $dto->rules());

		if (count($violations) > 0) {
			$errors = [];
			foreach ($violations as $violation) {
				$errors[$violation->getPropertyPath()][] = $violation->getMessage();
			}

			throw new ValidationException($errors);
		}

		return $dto;
	}
}
