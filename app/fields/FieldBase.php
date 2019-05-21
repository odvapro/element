<?php
/**
 * Abstract class for field type
 */
abstract class FieldBase extends Phalcon\Mvc\User\Plugin
{
	protected $fieldValue = '';
	protected $settings   = [];
	public function __construct($fieldValue = '', $settings = [])
	{
		$this->fieldValue = $fieldValue;
		$this->settings   = $settings;
	}
	abstract function getValue();
	abstract function saveValue();
}