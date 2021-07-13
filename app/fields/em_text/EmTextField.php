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
		return json_decode($this->fieldValue,true);
	}
	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		return json_encode($this->fieldValue);
	}
}
