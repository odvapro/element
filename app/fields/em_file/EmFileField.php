<?php

class EmFileField extends FieldBase
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
		$domain = $this->di->get('config')->application->domain;
		$resArray = json_decode($this->fieldValue, true);
		if(empty($resArray)) return false;

		foreach ($resArray as &$image)
		{
			$image['path'] = $domain.$image['path'];
			foreach ($image['sizes'] as &$imageSize)
				$imageSize = $domain.$imageSize;
		}
		return $resArray;
	}
	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{

	}
}