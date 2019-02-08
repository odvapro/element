<?php

class EmImageField extends FieldBase
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
		return json_decode($this->fieldValue, true);
	}
	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{

	}
}