<?php

class EmTextField extends FieldBase
{
	protected $fieldValue = '';
	/**
	 * Конструктор принимает значение поля
	 */
	public function __construct($fieldValue = '')
	{
		$this->fieldValue = $fieldValue;
	}

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		return strval($this->fieldValue);
	}
	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		return strval($this->fieldValue);
	}
}