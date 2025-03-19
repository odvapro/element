<?php

namespace Element\Validation;

use Symfony\Component\Validator\Constraint;

interface ValidationInterface
{
	public function rules(): Constraint;
}