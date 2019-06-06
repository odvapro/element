<?php

class EmTypes extends ModelBase
{
	/**
	 * Закрытие настроек поля
	 * @var String
	 */
	protected $settings;

	/**
	 * Конвертирование настроек в массив
	 * @return Array Массив настроек
	 */
	public function getSettings()
	{
		return json_decode($this->settings, true);
	}

	/**
	 * Запись настроек
	 * @param Array $settings Массив настроек
	 */
	public function setSettings($settings)
	{
		$this->settings = json_encode($settings);
	}

	/**
	 * Обязательное поле
	 * @return bool
	 */
	public function getRequired()
	{
		if($this->required === '1')
			return true;
		return false;
	}

	/**
	 * Преобразование в массив
	 * @param  Array $columns Массив полей которые нужны
	 * @return Array          Массив полей модели
	 */
	public function toArray($columns = NULL)
	{
		$result             = parent::toArray($columns);
		$result['settings'] = $this->getSettings();

		return $result;
	}
}