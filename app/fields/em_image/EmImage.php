<?php

class EmImage extends FieldBase
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
		return json_decode($this->value, true);
	}
	public function saveValue()
	{

	}
}