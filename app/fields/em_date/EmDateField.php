<?php

class EmDateField extends FieldBase
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
		return strval(strip_tags($this->fieldValue));
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if(empty($this->fieldValue))
			return NULL;
		return $this->fieldValue;
	}
}