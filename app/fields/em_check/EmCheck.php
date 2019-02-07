<?php

class EmCheck extends FieldBase
{
	protected $value = '';

	public function __construct($value = '')
	{
		$this->value = $value;
	}

	public function setSettings()
	{

	}
	public function getValue()
	{
		return $this->value === '1' ? true : false;
	}
	public function saveValue()
	{

	}
}