<?php

class EmDateField extends FieldBase
{
	protected $fieldValue = '';
	protected $settings = [];
	/**
	 * Конструктор принимает значение поля
	 */
	public function __construct($fieldValue = '', array $settings = [])
	{
		$this->fieldValue = $fieldValue;
		$this->settings = $settings;
	}

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		return strval(strip_tags($this->fieldValue));
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if(empty($this->fieldValue) || strtotime($this->fieldValue) === false)
			return NULL;
		return $this->fieldValue;
	}
}