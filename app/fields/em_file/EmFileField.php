<?php

class EmFileField extends FieldBase
{
	protected $fieldValue = '';
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
		$domain   = $this->di->get('config')->application->domain;
		$resArray = json_decode($this->fieldValue, true);

		if(empty($resArray)) return false;

		foreach ($resArray as &$image)
		{
			$image['localPath'] = $image['path'];
			$image['path']      = $domain.$image['path'];

			foreach ($image['sizes'] as &$imageSize)
				$imageSize = [
					'path'      => $domain.$imageSize,
					'localPath' => $imageSize,
				];
		}

		return $resArray;
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if(!is_array($this->fieldValue))
			return ($this->fieldValue == 'false') ? '' : $this->fieldValue;

		if(empty($this->fieldValue))
			return '';

		foreach($this->fieldValue as &$image)
		{
			$image['path'] = $image['localPath'];
			unset($image['localPath']);

			foreach ($image['sizes'] as &$imageSize)
				$imageSize = $imageSize['localPath'];
		}

		return json_encode($this->fieldValue);
	}

	/**
	 * Получить настройки поля
	 * @return Array Настройки поля
	 */
	public function getSettings()
	{
		$settings             = parent::getSettings();
		$settings['rootPath'] = realpath(ROOT . '/../') . '/';

		return $settings;
	}
}