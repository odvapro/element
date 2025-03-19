<?php

namespace Element\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;

class InstallRequest extends BaseRequest
{
	public function rules(): Constraint
	{
		return new Assert\Collection([
			'host' => new Assert\Required([new Assert\Type('numeric')]),
		]);
	}
}
