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
		$result = json_decode($this->fieldValue ?? '',true);
		if(json_last_error() === JSON_ERROR_NONE)
			return $result;

		return $this->fieldValue;
	}
	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		return json_encode($this->fieldValue);
	}
}
