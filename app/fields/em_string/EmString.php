<?php

abstract class EmString extends FieldBase
{
	public function setSettings()
	{

	}
	public function getValue($value)
	{
		echo "<pre>";
		print_r($value);
		exit();
	}
	public function saveValue()
	{

	}
}