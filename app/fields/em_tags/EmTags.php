<?php

class EmTags extends FieldBase
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
		return $this->value;
	}
	public function saveValue()
	{

	}
}