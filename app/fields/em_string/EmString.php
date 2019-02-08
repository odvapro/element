<?php

class EmString extends FieldBase
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
	 * Добавить настройки для поля
	 */
	public function setSettings()
	{

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

	}
}