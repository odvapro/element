<?php

class CusTestField extends FieldBase
{
	public function getValue()
	{
		return strval(strip_tags($this->fieldValue));
	}

	public function saveValue()
	{
		return $this->fieldValue;
	}
}