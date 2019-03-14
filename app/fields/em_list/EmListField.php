<?php

class EmListField extends FieldBase
{
	protected $fieldValue = '';
	protected $columns = [];

	/**
	 * Конструктор принимает значение поля
	 */
	public function __construct($fieldValue = '', $tableCode = '', $columns = [])
	{
		$this->fieldValue = $fieldValue;
		$this->columns    = $columns;
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
		$settingsList = $this->columns['em']['settings']['list'];

		if (empty($settingsList))
			return $this->fieldValue;

		foreach ($settingsList as $listItem)
			if ($listItem['key'] == $this->fieldValue)
				return $listItem['value'];

		return $this->fieldValue;
	}
	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{

	}
}