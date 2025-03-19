<?php

namespace Element\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;

use Element\Validation\ValidationInterface;
use Element\Exceptions\ValidationException;

class BaseRequest implements ValidationInterface
{
	protected array $query;

	public function rules(): Constraint
	{
		return new Assert\Collection([]);
	}

	public function fromHttp(Request $request): self
	{
		try {
			$this->query = $request->toArray();
		} catch (\Exception $e) {
			throw new ValidationException($e->getMessage());
		}

		return $this;
	}
}